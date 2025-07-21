<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Curso;     // ✅ Adicionado: Importar Curso para o with()
use App\Models\Instituicao; // ✅ Adicionado: Importar Instituicao para o with()
use Illuminate\Http\Request;

class ClassificacaoController extends Controller
{
    public function index()
    {
        // 1. Busca APENAS os candidatos com status 'Homologado' para a classificação completa
        $candidatosAvaliados = Candidato::where('status', 'Homologado') // ✅ CORRIGIDO: Filtra apenas por 'Homologado'
                                        ->with(['user', 'curso', 'instituicao']) // Incluído 'instituicao' para eager loading
                                        ->get();

        // 2. Mapeia os candidatos para incluir a pontuação final calculada e outros dados relevantes
        $candidatosComPontuacao = $candidatosAvaliados->map(function ($candidato) {
            // Assumimos que calcularPontuacaoDetalhada() é um método no Model Candidato
            $resultado = $candidato->calcularPontuacaoDetalhada();
            
            return (object) [ // Converte para objeto para facilitar o acesso na view
                'nome' => $candidato->nome_completo, // Usar 'nome_completo' do Candidato
                'email' => $candidato->email, // Mantenha se quiser exibir na completa
                'cpf' => $candidato->cpf,
                'status' => $candidato->status,
                'curso_nome' => $candidato->curso->nome ?? 'Não Informado', // Garante que não quebre se curso for nulo
                'instituicao_nome' => $candidato->instituicao->nome ?? 'Não Informada', // Adicionado para a view
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
                return $candidatosDoCurso
                    ->sortByDesc('pontuacao_final') // Maior pontuação primeiro
                    ->sortBy(function($candidato) {
                        return strtotime($candidato->data_nascimento); // Converte para timestamp para ordenação de data
                    })
                    ->values(); // Reseta os índices do array
            });

        // 4. Envia a classificação agrupada para a view
        return view('classificacao.index', compact('classificacaoPorCurso'));
    }
}