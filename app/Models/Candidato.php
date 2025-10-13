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
use Illuminate\Database\Eloquent\Casts\Attribute; 
use App\Support\NameCase; 


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
        'instituicao_id', 
        'homologado_em', 
        'homologacao_observacoes',
        'revert_reason',
        'recurso_texto',
        'recurso_prazo_ate',
        'recurso_status',
        'recurso_tipo',
        'recurso_historico',
        'convocado_em',
        'lotacao_local',
        'lotacao_chefia',
        'lotacao_observacoes',
        'contrato_data_inicio',
        'contrato_data_fim',
        'prorrogacao_data_inicio',
        'prorrogacao_data_fim',
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
        'pontuacao_final' => 'float', 
        'homologado_em' => 'datetime', 
        'revert_reason' => 'array', 
        'recurso_prazo_ate' => 'datetime',
        'recurso_historico' => 'array', 
        'convocado_em' => 'datetime',
        'contrato_data_inicio' => 'date',
        'contrato_data_fim' => 'date',
        'prorrogacao_data_inicio' => 'date',
        'prorrogacao_data_fim' => 'date',
        'last_contacted_at'    => 'datetime',
    ];

    protected $appends = [
    'completion_percentage',
    'pontuacao_final',
    'perfil_pdf_url',
    'convocacao_pdf_url',
    'nome_completo_formatado', 
];

    /**
     * O método "booted" do modelo.
     */
    protected static function booted()
    {
        static::deleting(function ($candidato) {
            Log::info("Iniciando a exclusão em cascata para o Candidato ID: {$candidato->id}");
            $candidato->load('documentos', 'atividades');

            // 1. Apagar atividades e seus ficheiros
            foreach ($candidato->atividades as $atividade) {
                if ($atividade->comprovativo_path && Storage::disk('public')->exists($atividade->comprovativo_path)) {
                    Storage::disk('public')->delete($atividade->comprovativo_path);
                }
                $atividade->delete();
            }

            // 2. Apagar documentos e seus ficheiros
            foreach ($candidato->documentos as $documento) {
                if ($documento->path && Storage::disk('public')->exists($documento->path)) {
                    Storage::disk('public')->delete($documento->path);
                }
                $documento->delete();
            }
        });
    }

public function getConvocacaoPdfUrlAttribute()
{
    if ($this->status === 'Convocado') {
        return route('admin.candidatos.convocacao.pdf', $this->id); // usar o nome correto
    }
    return null;
}

// Retorna só dígitos do telefone (ex.: "(65) 99999-9999" -> "65999999999")
public function getTelefoneDigitsAttribute(): ?string
{
    $digits = preg_replace('/\D+/', '', (string) $this->telefone);
    return $digits ?: null;
}

// Retorna em E.164 presumindo Brasil (55). Ex.: "65999999999" -> "5565999999999"
public function getTelefoneE164Attribute(): ?string
{
    $d = $this->telefone_digits;
    if (!$d) return null;

    // Já vem com 55?
    if (str_starts_with($d, '55')) return $d;

    // 10 ou 11 dígitos (DDD + número)
    if (strlen($d) >= 10 && strlen($d) <= 11) {
        return '55' . $d;
    }

    // Qualquer outro caso: deixa nulo para bloquear o botão
    return null;
}

// Telefone "tem cara" de WhatsApp utilizável (10/11 dígitos BR)
public function getHasWhatsappAttribute(): bool
{
    $d = $this->telefone_digits;
    return $d && (strlen($d) === 11 || strlen($d) === 10);
}

    
    /**
     * Determina se o candidato pode interpor recurso após a homologação.
     * Acessível via: $candidato->pode_interpor_recurso
     */
    protected function podeInterporRecurso(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                // ✅ AJUSTE: Usamos strtolower() para que a verificação não se importe com maiúsculas/minúsculas.
                if (strtolower($this->status) !== 'homologado' || is_null($this->homologado_em)) {
                    return false;
                }

                // Verifica se já existe um recurso pendente no histórico.
                if (!empty($this->recurso_historico)) {
                    $recursoMaisRecente = $this->recurso_historico[0];
                    if (empty($recursoMaisRecente['decisao_admin'])) {
                        return false; // Já existe um recurso em análise, não pode criar outro.
                    }
                }

                $prazoFinal = $this->homologado_em->addDays(2);

                return now()->lessThanOrEqualTo($prazoFinal);
            }
        );
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
 * Calcula a pontuação total com base nas atividades e a salva no banco de dados.
 */
public function updateAndSaveScore()
{
    // Chama o método que já existe para calcular a pontuação detalhada
    $pontuacaoDetalhada = $this->calcularPontuacaoDetalhada();
    $totalScore = $pontuacaoDetalhada['total'] ?? 0;

    //CORREÇÃO AQUI: Usando o nome de coluna correto do banco de dados
    $this->pontuacao_final = $totalScore;
    $this->save();

    return $totalScore;
}

public function getPontuacaoFinalAttribute()
{
    // Se já existe valor salvo no banco, use ele
    if (isset($this->attributes['pontuacao_final']) && $this->attributes['pontuacao_final'] > 0) {
        return $this->attributes['pontuacao_final'];
    }

     
    // Caso contrário, calcule
    if (method_exists($this, 'calcularPontuacaoDetalhada')) {
        $pontuacaoDetalhada = $this->calcularPontuacaoDetalhada();
        return $pontuacaoDetalhada['total'] ?? 0;
    }
    
    return 0;
}

public function getNomeCompletoFormatadoAttribute(): string
{
    return NameCase::person((string)($this->attributes['nome_completo'] ?? ''));
}

    
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

public function getPerfilPdfUrlAttribute()
{
    return route('admin.candidatos.perfil.pdf', ['candidato' => $this->id]);
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
            $value = $this->{$field};
            if ($field === 'possui_deficiencia') {
                if ($value !== null) {
                    $filledFields++;
                }
            } 
            else if ($value !== null && $value !== '') {
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

// App/Models/Candidato.php

public function isProfileComplete(): bool
{
    foreach (self::getCompletableFields() as $field) {
        $value = $this->{$field};
        if ($field === 'possui_deficiencia') {
            if ($value === null) return false;
        } else {
            if (is_string($value)) {
                if (trim($value) === '') return false;
            } else {
                if ($value === null) return false;
            }
        }
    }
    return true;
}

/** Alias para padronizar as checagens */
public function isComplete(): bool
{
    return $this->isProfileComplete();
}

    
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