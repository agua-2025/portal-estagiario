<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Curso; 
use App\Models\Instituicao; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Log; 

class Candidato extends Model
{
    use HasFactory;

    protected $table = 'candidatos';

    /**
     * The attributes that are mass assignable.
     * Inclui todas as colunas que podem ser preenchidas via formulários ou código.
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
        'pontuacao_final', 
        'status', 
        'admin_observacao', 
        'instituicao_id', 
        'ato_homologacao', 
        'homologado_em', 
        'homologacao_observacoes',
    ];

    /**
     * The attributes that should be cast.
     * Converte automaticamente strings do BD para tipos PHP.
     * ✅ AQUI ESTÁ A CORREÇÃO CRÍTICA PARA 'homologado_em'
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'curso_data_inicio' => 'date',
        'curso_previsao_conclusao' => 'date',
        'possui_deficiencia' => 'boolean',
        'media_aproveitamento' => 'float', 
        'semestres_completos' => 'integer',
        'pontuacao_final' => 'float', 
        'homologado_em' => 'datetime', // ✅ CRÍTICO: ESTA LINHA VAI RESOLVER O ERRO
    ];

    /**
     * Atributos que devem ser anexados à representação do array/JSON do modelo.
     * 'completion_percentage' é um atributo virtual calculado.
     */
    protected $appends = ['completion_percentage']; 

    /**
     * Define a relação de que um Candidato pertence a um Usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a relação de que um Candidato pertence a um Curso.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Define a relação de que um Candidato pertence a uma Instituição.
     */
    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }

    /**
     * Retorna uma lista dos campos que devem ser considerados para o cálculo da porcentagem de preenchimento do perfil.
     */
    public static function getCompletableFields(): array
    {
        return [
            'nome_completo', 'nome_mae', 'data_nascimento', 'sexo', 'cpf', 
            'naturalidade_cidade', 'naturalidade_estado',
            'possui_deficiencia',
            'telefone', 'cep', 'logradouro', 
            'numero', 
            'bairro',
            'cidade', 
            'estado', 
            'curso_id', 
            'instituicao_id', 
            'curso_data_inicio',
            'curso_previsao_conclusao',
            'media_aproveitamento',
            'semestres_completos',
        ];
    }

    /**
     * Calcula a porcentagem de preenchimento do perfil do candidato.
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
                if ($value === 0 || $value === 1) { 
                    $filledFields++;
                }
            } 
            else if ($value !== null && $value !== '') {
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    /**
     * Calcula a pontuação total do candidato com base nas atividades aprovadas e retorna os detalhes.
     * Versão robusta com logs e tratamento de regras.
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
            $pontosPorUnidade = abs($regra->pontos_por_unidade ?? 0); 
            
            Log::debug("Processando atividade '{$atividade->descricao_customizada}' (ID: {$atividade->id}) com regra '{$nomeDaRegra}' (ID: {$regra->id}, Unidade: {$regra->unidade_medida})");
            Log::debug("Pontos por Unidade da Regra: {$pontosPorUnidade}");

            // Lógica para "Número de semestres cursados"
            if (str_contains(strtolower($nomeDaRegra), 'número de semestres cursados') || $regra->unidade_medida === 'semestre') {
                $semestresDeclaradosNaAtividade = $atividade->semestres_declarados ?? 0; 
                $pontosPorSemestre = (float) $pontosPorUnidade; 

                if ($pontosPorSemestre <= 0) {
                    Log::debug("Regra 'Número de semestres cursados': Pontos por unidade configurado como <= 0. 0 pontos.");
                    $pontosCalculados = 0;
                } else {
                    $pontosCalculados = $semestresDeclaradosNaAtividade * $pontosPorSemestre;
                }
                
                $pontosDaAtividade = isset($regra->pontuacao_maxima) && $regra->pontuacao_maxima > 0 
                    ? min($pontosCalculados, $regra->pontuacao_maxima) 
                    : $pontosCalculados;
                Log::debug("Pontos calculados para 'semestres cursados' (usando semestres_declarados da ATIVIDADE): {$pontosCalculados}, Pontos da atividade (com max): {$pontosDaAtividade}");

            } 
            // Bloco para "Aproveitamento Acadêmico"
            elseif (strtolower($nomeDaRegra) === 'aproveitamento acadêmico') {
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
            // Lógica padrão para outras regras baseadas em unidade de medida
            else { 
                if ($pontosPorUnidade <= 0) {
                    Log::debug("Regra '{$nomeDaRegra}' (ID: {$regra->id}) tem pontos_por_unidade <= 0. Pulando.");
                    continue; 
                }

                switch ($regra->unidade_medida) {
                    case 'horas':
                        $divisor = abs($regra->divisor_unidade ?? 30);
                        $divisor = $divisor > 0 ? $divisor : 30; 
                        
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
                        $divisor = $divisor > 0 ? $divisor : 6; 
                        
                        Log::debug("Tipo 'meses': Data Início: {$atividade->data_inicio}, Data Fim: {$atividade->data_fim}, Divisor: {$divisor}");

                        if ($atividade->data_inicio && $atividade->data_fim) {
                            $dataInicio = Carbon::parse($atividade->data_inicio);
                            $dataFim = Carbon::parse($atividade->data_fim);
                            
                            if ($dataFim->gte($dataInicio)) { 
                                $mesesCompletos = $dataInicio->diffInMonths($dataFim);
                                
                                if ($dataFim->day >= $dataInicio->day && $dataFim->diffInDays($dataInicio) > 0) { 
                                    $mesesCompletos += 1;
                                }
                                
                                Log::debug("Meses de Diferença (diffInMonths): {$dataInicio->diffInMonths($dataFim)}");
                                Log::debug("Meses Completos (ajustado para pontuação): {$mesesCompletos}");

                                $pontosCalculados = floor($mesesCompletos / $divisor) * $pontosPorUnidade;
                                $pontosDaAtividade = isset($regra->pontuacao_maxima) && $regra->pontuacao_maxima > 0 
                                    ? min($pontosCalculados, $regra->pontuacao_maxima) 
                                    : $pontosCalculados;
                            } else {
                                Log::debug("Data Fim ({$dataFim->toDateString()}) não é maior ou igual à Data Início ({$dataInicio->toDateString()}). 0 pontos.");
                            }
                        } else {
                            Log::debug("Data de início ou fim ausente. 0 pontos para esta atividade.");
                        }
                        break;

                    case 'fixo':
                        Log::debug("Tipo 'fixo': Nome da Regra: '{$nomeDaRegra}'");
                        // Lógica para "Aproveitamento Acadêmico" (já tratada acima, este else 'fixo' é para outros tipos fixos)
                        $pontosDaAtividade = $pontosPorUnidade;
                        Log::debug("Regra fixa padrão (não específica). Pontos: {$pontosDaAtividade}");
                        break;
                }
            } 

            // Proteção final: Garante que a pontuação nunca seja negativa
            $pontosDaAtividade = max(0, $pontosDaAtividade);

            $pontuacaoTotal += $pontosDaAtividade;
            $detalhes[] = ['nome' => $nomeDaRegra, 'pontos' => $pontosDaAtividade];
            Log::debug("Pontuação acumulada até agora: {$pontuacaoTotal}");
        }

        Log::debug('Cálculo de pontuação finalizado. Total: ' . $pontuacaoTotal);
        return [
            'total' => max(0, $pontuacaoTotal),
            'detalhes' => $detalhes,
        ];
    }
}