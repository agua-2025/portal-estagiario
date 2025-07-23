<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Curso;
use App\Models\Instituicao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Candidato extends Model
{
    use HasFactory;

    protected $table = 'candidatos';

    /**
     * The attributes that are mass assignable.
     * ✅ AJUSTE: Garante que todos os campos do formulário, incluindo chaves estrangeiras, estão aqui.
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
        'cidade_id', // ✅ CORRIGIDO: Usando cidade_id
        'estado_id', // ✅ CORRIGIDO: Usando estado_id
        'cidade', // Mantido por segurança, caso ainda seja usado em algum lugar
        'estado', // Mantido por segurança, caso ainda seja usado em algum lugar
        'cep', 
        'naturalidade_cidade', 
        'naturalidade_estado',
        'telefone_celular', // ✅ CORRIGIDO: Nome mais específico
        'telefone', // Mantido por segurança
        'possui_deficiencia', 
        'curso_data_inicio',
        'curso_previsao_conclusao', 
        'media_aproveitamento', 
        'periodo_ou_semestre', // ✅ CORRIGIDO: Nome mais claro
        'semestres_completos', // Mantido por segurança
        'status', 
        'admin_observacao',
        'instituicao_id', 
        'ato_homologacao', 
        'homologado_em', 
        'homologacao_observacoes',
        'revert_reason',
        'recurso_texto',
        'recurso_prazo_ate',
        'recurso_status',
        'recurso_tipo',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'curso_data_inicio' => 'date',
        'curso_previsao_conclusao' => 'date',
        'possui_deficiencia' => 'boolean',
        'media_aproveitamento' => 'float',
        'semestres_completos' => 'integer',
        'periodo_ou_semestre' => 'integer',
        'pontuacao_final' => 'float', 
        'homologado_em' => 'datetime', 
        'revert_reason' => 'array', 
        'recurso_prazo_ate' => 'datetime',
    ];

    protected $appends = ['completion_percentage']; 

    /**
     * O método "booted" do modelo.
     */
    protected static function booted()
    {
        static::deleting(function ($candidato) {
            Log::info("Iniciando a exclusão em cascata para o Candidato ID: {$candidato->id}");
            $candidato->load('documentos', 'atividades');

            foreach ($candidato->atividades as $atividade) {
                if ($atividade->comprovativo_path && Storage::disk('public')->exists($atividade->comprovativo_path)) {
                    Storage::disk('public')->delete($atividade->comprovativo_path);
                }
                $atividade->delete();
            }

            foreach ($candidato->documentos as $documento) {
                if ($documento->path && Storage::disk('public')->exists($documento->path)) {
                    Storage::disk('public')->delete($documento->path);
                }
                $documento->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function atividades()
    {
        return $this->hasMany(CandidatoAtividade::class);
    }

    /**
     * ✅ AJUSTE: Retorna a lista de campos essenciais para o perfil ser considerado completo.
     * Esta lista deve corresponder aos 'name' dos seus inputs no formulário de perfil.
     */
    public static function getCompletableFields(): array
    {
        return [
            'nome_completo', 'nome_mae', 'data_nascimento', 'sexo', 'cpf', 
            'naturalidade_cidade', 'naturalidade_estado',
            'possui_deficiencia',
            'telefone_celular', // Usando nome mais específico
            'cep', 'logradouro', 'numero', 'bairro',
            'cidade_id', // Usando ID
            'estado_id', // Usando ID
            'instituicao_id',
            'curso_id', 
            'curso_data_inicio',
            'curso_previsao_conclusao',
            'periodo_ou_semestre', // Usando nome mais claro
            'media_aproveitamento',
        ];
    }

    public function getCompletionPercentageAttribute()
    {
        $fields = self::getCompletableFields();
        $totalFields = count($fields);
        if ($totalFields === 0) {
            return 0;
        }

        $filledFields = 0;
        foreach ($fields as $field) {
            if (!empty($this->{$field}) || $this->{$field} === false || $this->{$field} === 0) {
                 $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    public function isProfileComplete(): bool
    {
        // A porcentagem de conclusão já nos diz se o perfil está completo.
        return $this->getCompletionPercentageAttribute() === 100;
    }
    
    public function getFirstIncompleteField(): ?string
    {
        // Mapeia o nome do campo para um nome amigável para o usuário
        $fieldNames = [
            'nome_completo' => 'Nome Completo',
            'nome_mae' => 'Nome da Mãe',
            'data_nascimento' => 'Data de Nascimento',
            'sexo' => 'Sexo',
            'cpf' => 'CPF',
            'naturalidade_cidade' => 'Cidade Natal',
            'naturalidade_estado' => 'Estado Natal',
            'possui_deficiencia' => 'Informação sobre Deficiência',
            'telefone_celular' => 'Telefone Celular',
            'cep' => 'CEP',
            'logradouro' => 'Endereço',
            'numero' => 'Número do Endereço',
            'bairro' => 'Bairro',
            'cidade_id' => 'Cidade',
            'estado_id' => 'Estado',
            'instituicao_id' => 'Instituição de Ensino',
            'curso_id' => 'Curso',
            'curso_data_inicio' => 'Data de Início do Curso',
            'curso_previsao_conclusao' => 'Previsão de Conclusão',
            'periodo_ou_semestre' => 'Período ou Semestre',
            'media_aproveitamento' => 'Média de Aproveitamento',
        ];

        foreach (self::getCompletableFields() as $field) {
            if (empty($this->{$field}) && $this->{$field} !== false && $this->{$field} !== 0) {
                return $fieldNames[$field] ?? $field; // Retorna o nome amigável
            }
        }
        return null;
    }

    public function calcularPontuacaoDetalhada()
    {
        Log::debug('Iniciando cálculo de pontuação detalhada para Candidato ID: ' . $this->id);
        $pontuacaoTotal = 0;
        $detalhes = [];

        $atividadesAprovadas = $this->atividades()->where('status', 'Aprovada')->with('tipoDeAtividade')->get();

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

            if (str_contains(strtolower($nomeDaRegra), 'número de semestres cursados') || $regra->unidade_medida === 'semestre') {
                $semestresDeclaradosNaAtividade = $atividade->semestres_declarados ?? 0; 
                $pontosPorSemestre = (float) $pontosPorUnidade; 
                if ($pontosPorSemestre <= 0) {
                    $pontosCalculados = 0;
                } else {
                    $pontosCalculados = $semestresDeclaradosNaAtividade * $pontosPorSemestre;
                }
                $pontosDaAtividade = isset($regra->pontuacao_maxima) && $regra->pontuacao_maxima > 0 
                    ? min($pontosCalculados, $regra->pontuacao_maxima) 
                    : $pontosCalculados;
                Log::debug("Pontos calculados para 'semestres cursados' (usando semestres_declarados da ATIVIDADE): {$pontosCalculados}, Pontos da atividade (com max): {$pontosDaAtividade}");
            } 
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
                            $pontosDaAtividade = $pontosPorUnidade;
                            Log::debug("Regra fixa padrão (não específica). Pontos: {$pontosDaAtividade}");
                        }
                        break;
                }
            }
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