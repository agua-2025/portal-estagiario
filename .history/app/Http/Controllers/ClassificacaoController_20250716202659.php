<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use Illuminate\Http\Request;
use App\Models\Curso;     // ✅ Adicionado: Importar Curso para o with()
use App\Models\Instituicao; // ✅ Adicionado: Importar Instituicao para o with()

class ClassificacaoController extends Controller
{
    public function index()
    {
        // 1. Busca os candidatos que já foram avaliados (Aprovado ou Rejeitado)
        // ✅ CORREÇÃO: Adicionado 'instituicao' ao eager loading para que o relacionamento esteja disponível.
        $candidatosAvaliados = Candidato::whereIn('status', ['Aprovado', 'Rejeitado'])
                                        ->with(['user', 'curso', 'instituicao']) // ✅ Incluído 'instituicao'
                                        ->get();

        // 2. Mapeia os candidatos para incluir a pontuação final calculada e outros dados relevantes
        $candidatosComPontuacao = $candidatosAvaliados->map(function ($candidato) {
            // Assumimos que calcularPontuacaoDetalhada() é um método no Model Candidato
            $resultado = $candidato->calcularPontuacaoDetalhada();
            
            return (object) [ // Converte para objeto para facilitar o acesso na view
                // ✅ RECOMENDAÇÃO: Usar 'nome_completo' do Candidato para consistência com o WelcomeController
                // Se o nome completo estiver no User model, mantenha $candidato->user->name
                'nome' => $candidato->nome_completo, // Ou $candidato->user->name se preferir
                'email' => $candidato->email, // ✅ Adicionado email, útil na classificação completa
                'cpf' => $candidato->cpf,
                'status' => $candidato->status,
                'curso_nome' => $candidato->curso->nome ?? 'Não Informado', // Garante que não quebre se curso for nulo
                'instituicao_nome' => $candidato->instituicao->nome ?? 'Não Informada', // ✅ Adicionado para a view
                'data_nascimento' => $candidato->data_nascimento, // Essencial para o critério de desempate
                'pontuacao_final' => $resultado['total'],
                'pontuacao_detalhes' => $resultado['detalhes'],
            ];
        });

        // 3. Agrupa os candidatos por curso e ordena dentro de cada grupo
        $classificacaoPorCurso = $candidatosComPontuacao
            ->groupBy('curso_nome')
            ->map(function ($candidatosDoCurso) {
                // ORDENAÇÃO DUPLA: Primeiro por pontos (maior para o menor), depois por idade (mais velho primeiro)
                // ✅ IMPORTANTE: sortBy('data_nascimento') ordena do mais NOVO para o mais VELHO.
                // Para mais VELHO primeiro, use sortBy(fn($c) => strtotime($c->data_nascimento)) ou similar
                // OU, se data_nascimento for um Carbon instance: ->sortBy('data_nascimento')
                // Se a intenção é "mais velho primeiro", a ordenação padrão de data funciona.
                return $candidatosDoCurso
                    ->sortByDesc('pontuacao_final') // Maior pontuação primeiro
                    ->sortBy('data_nascimento')     // Em caso de empate, mais velho primeiro (se a data for menor)
                    ->values(); // Reseta os índices do array
            });

        // 4. Envia a classificação agrupada para a view
        return view('classificacao.index', compact('classificacaoPorCurso'));
    }
}