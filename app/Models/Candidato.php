<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Curso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Adicionado para logs de depuração no cálculo de pontuação

class Candidato extends Model
{
    use HasFactory;

    protected $table = 'candidatos';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 
        'nome_completo', 
        'curso_id', 
        'nome_pai', 
        'nome_mae', 
        'data_nascimento', 
        'sexo',
        'cpf', 
        'rg', 
        'rg_orgao_expedidor', 
        'logradouro', 
        'numero', 
        'bairro',
        'cidade', 
        'estado', 
        'cep', 
        'naturalidade_cidade', 
        'naturalidade_estado',
        'telefone', 
        'possui_deficiencia', 
        'curso_data_inicio',
        'curso_previsao_conclusao', 
        'media_aproveitamento', 
        'semestres_completos',
        'status', 
        'admin_observacao',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'curso_data_inicio' => 'date',
        'curso_previsao_conclusao' => 'date',
        'possui_deficiencia' => 'boolean',
        'media_aproveitamento' => 'float', // ✅ ADICIONADO: Garante que a média é um float
        'semestres_completos' => 'integer', // ✅ ADICIONADO: Garante que semestres é um inteiro
    ];

    // Garante que os atributos virtuais sejam incluídos.
    protected $appends = ['instituicao_id', 'completion_percentage']; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Accessor para obter o ID da instituição através do curso relacionado.
     */
    public function getInstituicaoIdAttribute(): ?int
    {
        return $this->curso ? $this->curso->instituicao_id : null;
    }

    public static function getCompletableFields(): array
    {
        return [
            'nome_completo', 'nome_mae', 'data_nascimento', 'sexo', 'cpf', 
            'naturalidade_cidade', 'naturalidade_estado',
            'possui_deficiencia',
            'telefone', 'cep', 'logradouro', 'numero',
            'bairro', 'cidade', 'estado',
            'curso_id', // Este é o ID do curso, que indiretamente indica a instituição
            'curso_data_inicio',
            'curso_previsao_conclusao',
            'media_aproveitamento',
            'semestres_completos',
        ];
    }

    /**
     * Calcula a percentagem de preenchimento do perfil.
     */
    public function getCompletionPercentageAttribute()
    {
        $fields = self::getCompletableFields();
        $totalFields = count($fields);
        if ($totalFields === 0) {
            return 0;
        }

        $filledFields = 0;
        foreach ($fields as $field) {
            $value = $this->{$field};

            if ($field === 'possui_deficiencia') {
                if ($value !== null) { // Booleans false/true são considerados preenchidos
                    $filledFields++;
                }
            } 
            else if ($value !== null && $value !== '') { // Verifica se não é nulo e não é string vazia
                // Para campos de texto como CPF, Telefone, etc., que podem ter máscaras,
                // é bom verificar se há caracteres numéricos após remover a máscara.
                // No entanto, para o cálculo de porcentagem, um valor não nulo/vazio é suficiente.
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    /**
     * Verifica de forma segura se o perfil está 100% completo.
     */
    public function isProfileComplete(): bool
    {
        foreach (self::getCompletableFields() as $field) {
            $value = $this->{$field};

            if ($field === 'possui_deficiencia') {
                if ($value === null) { // Se for null, não está completo
                    return false;
                }
            } else {
                if ($value === null || $value === '') { // Se for null ou string vazia, não está completo
                    return false;
                }
            }
        }

        return true;
    }
    
    /**
     * Função de debug para encontrar campos em falta.
     */
    public function getFirstIncompleteField(): ?string
    {
        foreach (self::getCompletableFields() as $field) {
            $value = $this->{$field};

            if ($field === 'possui_deficiencia') {
                if ($value === null) {
                    return $field;
                }
            } else {
                if ($value === null || $value === '') {
                    return $field;
                }
            }
        }

        return null;
    }

    /**
     * ✅ MÉTODO FINAL E ROBUSTO - VERSÃO CORRIGIDA COM DEBUG E LÓGICA DE SEMESTRES
     * Calcula a pontuação total do candidato e retorna os detalhes.
     * Proteção contra valores negativos em todas as operações.
     */
    public function calcularPontuacaoDetalhada()
    {
        Log::debug('Iniciando cálculo de pontuação detalhada para Candidato ID: ' . $this->id);
        $pontuacaoTotal = 0;
        $detalhes = [];

        // Certifique-se de que 'candidatoAtividades' está definido no User model e 'tipoDeAtividade' na CandidatoAtividade model
        $atividadesAprovadas = $this->user->candidatoAtividades()->where('status', 'Aprovada')->with('tipoDeAtividade')->get();

        foreach ($atividadesAprovadas as $atividade) {
            $regra = $atividade->tipoDeAtividade;
            $pontosDaAtividade = 0;

            if (!$regra) {
                Log::debug("Atividade ID {$atividade->id} sem regra de pontuação associada. Pulando.");
                continue;
            }

            $nomeDaRegra = $regra->nome;
            
            // ✅ REDE DE SEGURANÇA REFORÇADA: Garante que os pontos sejam sempre um número positivo.
            $pontosPorUnidade = abs($regra->pontos_por_unidade ?? 0);
            
            Log::debug("Processando atividade '{$atividade->descricao_customizada}' (ID: {$atividade->id}) com regra '{$nomeDaRegra}' (ID: {$regra->id}, Unidade: {$regra->unidade_medida})");
            Log::debug("Pontos por Unidade da Regra: {$pontosPorUnidade}");

            // ✅ CORREÇÃO CRÍTICA: Lógica para "Número de semestres cursados"
            // Esta regra AGORA USA semestres_declarados da ATIVIDADE, não do perfil.
            if (str_contains(strtolower($nomeDaRegra), 'número de semestres cursados') || $regra->unidade_medida === 'semestre') {
                // Pega semestres da ATIVIDADE, não do CANDIDATO
                $semestresDeclaradosNaAtividade = $atividade->semestres_declarados ?? 0; 
                
                $pontosPorSemestre = (float) $pontosPorUnidade; 

                if ($pontosPorSemestre <= 0) {
                    Log::debug("Regra 'Número de semestres cursados': Pontos por unidade configurado como <= 0. 0 pontos para esta atividade.");
                    $pontosCalculados = 0;
                } else {
                    $pontosCalculados = $semestresDeclaradosNaAtividade * $pontosPorSemestre;
                }
                
                $pontosDaAtividade = isset($regra->pontuacao_maxima) && $regra->pontuacao_maxima > 0 
                    ? min($pontosCalculados, $regra->pontuacao_maxima) 
                    : $pontosCalculados;
                Log::debug("Pontos calculados para 'semestres cursados' (usando semestres_declarados da ATIVIDADE): {$pontosCalculados}, Pontos da atividade (com max): {$pontosDaAtividade}");

            } 
            // ✅ NOVO: Bloco para "Aproveitamento Acadêmico" - AGORA USA media_declarada_atividade da ATIVIDADE
            elseif (strtolower($nomeDaRegra) === 'aproveitamento acadêmico') {
                // Pega a média da ATIVIDADE, não do CANDIDATO
                $mediaDeclaradaNaAtividade = $atividade->media_declarada_atividade ?? 0; 
                $notaDeCorte = $regra->divisor_unidade ?? 7; 
                
                Log::debug("Regra 'Aproveitamento Acadêmico': Média Declarada na Atividade: {$mediaDeclaradaNaAtividade}, Nota de Corte: {$notaDeCorte}");
                
                if ($mediaDeclaradaNaAtividade >= $notaDeCorte) {
                    $pontosDaAtividade = $pontosPorUnidade;
                    Log::debug("Média da atividade >= Nota de Corte. Pontos: {$pontosDaAtividade}");
                } else {
                    Log::debug("Média da atividade < Nota de Corte. 0 pontos.");
                }
            }
            // Fim do NOVO bloco de "Aproveitamento Acadêmico"
            
            else { // Se não for a regra específica de semestres cursados ou aproveitamento acadêmico, usa a lógica normal baseada na unidade de medida
                // ✅ PROTEÇÃO EXTRA: Se pontos_por_unidade for 0, pula esta atividade (exceto para as regras tratadas acima)
                if ($pontosPorUnidade <= 0) {
                    Log::debug("Regra '{$nomeDaRegra}' (ID: {$regra->id}) tem pontos_por_unidade <= 0. Pulando.");
                    continue; // Pula para a próxima atividade
                }

                switch ($regra->unidade_medida) {
                    case 'horas':
                        $divisor = abs($regra->divisor_unidade ?? 30);
                        $divisor = $divisor > 0 ? $divisor : 30; // Garante divisor positivo
                        
                        Log::debug("Tipo 'horas': Carga Horária: {$atividade->carga_horaria}, Divisor: {$divisor}");

                        if ($atividade->carga_horaria > 0) {
                            $pontosCalculados = floor($atividade->carga_horaria / $divisor) * $pontosPorUnidade;
                            $pontosDaAtividade = isset($regra->pontuacao_maxima) && $regra->pontuacao_maxima > 0 
                                ? min($pontosCalculados, $regra->pontuacao_maxima) 
                                : $pontosCalculados;
                            Log::debug("Pontos calculados para 'horas': {$pontosCalculados}, Pontos da atividade (com max): {$pontosDaAtividade}");
                        } else {
                            Log::debug("Carga horária é 0 ou negativa. 0 pontos para esta atividade.");
                        }
                        break;
                    
                    case 'meses':
                        $divisor = abs($regra->divisor_unidade ?? 6);
                        $divisor = $divisor > 0 ? $divisor : 6; // Garante divisor positivo
                        
                        Log::debug("Tipo 'meses': Data Início: {$atividade->data_inicio}, Data Fim: {$atividade->data_fim}, Divisor: {$divisor}");

                        if ($atividade->data_inicio && $atividade->data_fim) {
                            $dataInicio = Carbon::parse($atividade->data_inicio);
                            $dataFim = Carbon::parse($atividade->data_fim);
                            
                            // ✅ CORREÇÃO: Garante que a data fim seja maior ou igual à data início para processar
                            if ($dataFim->gte($dataInicio)) { // Use gte (greater than or equal)
                                // Calcula a diferença total em meses, considerando o dia do mês
                                $mesesCompletos = $dataInicio->diffInMonths($dataFim);
                                
                                // Se a data final é no mesmo dia ou depois do dia inicial do último mês,
                                // o mês atual conta como completo.
                                if ($dataFim->day >= $dataInicio->day && $dataFim->diffInDays($dataInicio) > 0) { 
                                    $mesesCompletos += 1;
                                }
                                
                                Log::debug("Meses de Diferença (diffInMonths): {$dataInicio->diffInMonths($dataFim)}");
                                Log::debug("Meses Completos (ajustado para pontuação): {$mesesCompletos}");

                                $pontosCalculados = floor($mesesCompletos / $divisor) * $pontosPorUnidade;
                                $pontosDaAtividade = isset($regra->pontuacao_maxima) && $regra->pontuacao_maxima > 0 
                                    ? min($pontosCalculados, $regra->pontuacao_maxima) 
                                    : $pontosCalculados;
                                Log::debug("Pontos calculados para 'meses': {$pontosCalculados}, Pontos da atividade (com max): {$pontosDaAtividade}");
                            } else {
                                Log::debug("Data Fim ({$dataFim->toDateString()}) não é maior ou igual à Data Início ({$dataInicio->toDateString()}). 0 pontos.");
                            }
                        } else {
                            Log::debug("Data de início ou fim ausente. 0 pontos para esta atividade.");
                        }
                        break;

                    case 'fixo':
                        Log::debug("Tipo 'fixo': Nome da Regra: '{$nomeDaRegra}'");
                        // Lógica para "Aproveitamento Acadêmico"
                        if (str_contains(strtolower($nomeDaRegra), 'aproveitamento acadêmico')) {
                            $notaDeCorte = $regra->divisor_unidade ?? 7; 
                            Log::debug("Aproveitamento Acadêmico: Média: {$this->media_aproveitamento}, Nota de Corte: {$notaDeCorte}");
                            if ($this->media_aproveitamento >= $notaDeCorte) {
                                $pontosDaAtividade = $pontosPorUnidade;
                                Log::debug("Média >= Nota de Corte. Pontos: {$pontosDaAtividade}");
                            } else {
                                Log::debug("Média < Nota de Corte. 0 pontos.");
                            }
                        } else {
                            // Default fixed rule behavior if not specifically handled
                            $pontosDaAtividade = $pontosPorUnidade;
                            Log::debug("Regra fixa padrão (não específica). Pontos: {$pontosDaAtividade}");
                        }
                        break;
                }
            } // Fim do else (se não for a regra de semestres cursados ou aproveitamento acadêmico)

            // ✅ PROTEÇÃO FINAL: Garante que a pontuação nunca seja negativa
            $pontosDaAtividade = max(0, $pontosDaAtividade);

            $pontuacaoTotal += $pontosDaAtividade;
            $detalhes[] = ['nome' => $nomeDaRegra, 'pontos' => $pontosDaAtividade];
            Log::debug("Pontuação acumulada até agora: {$pontuacaoTotal}");
        }

        Log::debug('Cálculo de pontuação finalizado. Total: ' . $pontuacaoTotal);
        return [
            'total' => max(0, $pontuacaoTotal), // ✅ Garante que o total nunca seja negativo
            'detalhes' => $detalhes,
        ];
    }
}