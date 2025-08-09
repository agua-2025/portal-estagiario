<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\Documento;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Instituicao;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CandidatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Candidato::query()->with(['user', 'curso', 'instituicao']);

        if ($search) {
            $query->where('nome_completo', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
        }

        $candidatos = $query->latest()->paginate(15);
        return view('admin.candidatos.index', compact('candidatos', 'search'));
    }
    
    // =================================================================
    // ===== INÍCIO DA SEÇÃO DE RELATÓRIOS (VERSÃO CORRIGIDA) =====
    // =================================================================

    /**
     * Apenas carrega a página do construtor de relatórios com os dados para os filtros.
     */
    public function relatorios(Request $request)
{
    $cursos = \App\Models\Curso::orderBy('nome')->pluck('nome', 'id');
    $instituicoes = \App\Models\Instituicao::orderBy('nome')->pluck('nome', 'id');
    return view('admin.candidatos.relatorios', compact('cursos', 'instituicoes'));
}


    /**
     * Recebe os filtros via AJAX, consulta o banco e retorna os dados em JSON.
     */
public function filterAdvancedReports(Request $request)
{
    // DEBUG - INÍCIO DO MÉTODO
    \Log::info('=== DEBUG FILTRO RELATÓRIOS ===');
    \Log::info('Payload recebido:', [
        'colunas' => $request->input('colunas'),
        'filtros' => $request->input('filtros'),
        'todos_os_dados' => $request->all()
    ]);

    try {
        $colunas = $request->input('colunas', []);
        $filtros = $request->input('filtros', []);
        
        // Remove 'acoes' das colunas antes de fazer a query
        $colunasDb = array_filter($colunas, function($col) {
            return $col !== 'acoes';
        });
        
        // Adicione sempre o ID para poder gerar links
        if (!in_array('id', $colunasDb)) {
            $colunasDb[] = 'id';
        }
        
        // Iniciar a query
        $query = Candidato::query();
        
        // Adicionar joins necessários
        $query->leftJoin('cursos', 'candidatos.curso_id', '=', 'cursos.id')
              ->leftJoin('instituicoes', 'candidatos.instituicao_id', '=', 'instituicoes.id')
              ->leftJoin('users', 'candidatos.user_id', '=', 'users.id');
        
        // Aplicar filtros
        foreach ($filtros as $filtro) {
            $field = $filtro['field'] ?? null;
            $value = $filtro['value'] ?? null;
            
            if ($field && $value !== null && $value !== '') {
                if (isset($filtro['value_inicio']) && isset($filtro['value_fim'])) {
                    // Filtro de data
                    $query->whereBetween($field, [$filtro['value_inicio'], $filtro['value_fim']]);
                } elseif (isset($filtro['operator'])) {
                    // Filtro numérico com operador
                    $query->where($field, $filtro['operator'], $value);
                } else {
                    // Filtro simples
                    $query->where($field, $value);
                }
            }
        }
        
        // Selecionar colunas
        $selectColumns = [];
        foreach ($colunasDb as $col) {
            switch($col) {
                case 'curso_nome':
                    $selectColumns[] = 'cursos.nome as curso_nome';
                    break;
                case 'instituicao_nome':
                    $selectColumns[] = 'instituicoes.nome as instituicao_nome';
                    break;
                case 'pontuacao_final':
                    $selectColumns[] = 'candidatos.pontuacao_final'; // CORRIGIDO - nome correto da coluna
                    break;
                case 'email':
            $selectColumns[] = 'users.email as email';
            break;
                default:
                    if (strpos($col, '.') === false) {
                        $selectColumns[] = 'candidatos.' . $col;
                    } else {
                        $selectColumns[] = $col;
                    }
            }
        }
        
        // Adicionar sempre o ID
        if (!in_array('candidatos.id', $selectColumns)) {
            $selectColumns[] = 'candidatos.id';
        }
        
        $query->select($selectColumns);
        
        // Ordenar por nome
        $query->orderBy('candidatos.nome_completo');
        
        // Paginar resultados
        $results = $query->paginate(20);
        
        // Retornar resposta com paginação simplificada
        return response()->json([
            'data' => $results->items(),
            'links' => [], // Simplificado temporariamente
            'current_page' => $results->currentPage(),
            'total' => $results->total(),
            'per_page' => $results->perPage(),
            'last_page' => $results->lastPage()
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Erro no filtro de relatórios: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'error' => 'Erro ao processar filtro',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}

    /**
     * MÉTODO AUXILIAR PRIVADO
     * Constrói a consulta de candidatos com base nos filtros dinâmicos da request.
     */
    private function buildAdvancedReportQuery(Request $request)
{
    $query = \App\Models\Candidato::query()
                ->leftJoin('cursos', 'candidatos.curso_id', '=', 'cursos.id')
                ->leftJoin('instituicoes', 'candidatos.instituicao_id', '=', 'instituicoes.id')
                ->leftJoin('users', 'candidatos.user_id', '=', 'users.id') 
                ->select(
                    'candidatos.*', 
                    'cursos.nome as curso_nome', 
                    'instituicoes.nome as instituicao_nome',
                    'users.email as email'
                );

    // Verifica se o "pacote" de filtros foi enviado
    if ($request->has('filtros')) {
        foreach ($request->input('filtros', []) as $filter) {
            
            // Pula filtros inválidos ou vazios
            if (empty($filter['field'])) continue;

            $field = $filter['field']; // Ex: 'candidatos.status'

            // Lógica para filtros de período (daterange)
            if (!empty($filter['value_inicio']) && !empty($filter['value_fim'])) {
                $inicio = \Carbon\Carbon::parse($filter['value_inicio'])->startOfDay();
                $fim = \Carbon\Carbon::parse($filter['value_fim'])->endOfDay();
                $query->whereBetween($field, [$inicio, $fim]);
            
            // Lógica para outros tipos de filtro
            } elseif (isset($filter['value']) && $filter['value'] !== '') {
                $value = $filter['value'];
                $operator = $filter['operator'] ?? '=';

                // Se o campo for o nome, usa LIKE para busca textual
                if ($field === 'candidatos.nome_completo') {
                    $query->where($field, 'LIKE', '%' . $value . '%');
                } else {
                    // Para todos os outros (status, curso, pontuação, etc.), usa o operador
                    $query->where($field, $operator, $value);
                }
            }
        }
    }

    return $query;
}

 
public function exportarPdf(Request $request)
{
    try {
        $query = $this->buildAdvancedReportQuery($request);
        $candidatos = $query->orderBy('candidatos.nome_completo', 'asc')->get();

        $appliedFilters = [];
        if ($request->has('filtros')) {
            foreach ($request->input('filtros') as $filter) {
                if (!empty($filter['field'])) {
                    $value = $filter['value'] ?? ($filter['value_inicio'] . ' a ' . $filter['value_fim']);
                    if ($filter['field'] === 'candidatos.curso_id') {
                        $value = \App\Models\Curso::find($value)->nome ?? $value;
                    }
                    if ($filter['field'] === 'candidatos.instituicao_id') {
                        $value = \App\Models\Instituicao::find($value)->nome ?? $value;
                    }
                    $appliedFilters[ucwords(str_replace(['candidatos.', '_id', '_'], ' ', $filter['field']))] = $value;
                }
            }
        }
        
        $prefeituraInfo = [
            'nome' => env('PREFEITURA_NOME', 'Portal do Estagiário'),
            'endereco' => env('PREFEITURA_ENDERECO', 'Endereço da Prefeitura, Cidade, UF'),
            'telefone' => env('PREFEITURA_TELEFONE', '(00) 00000-0000'),
            'cnpj' => env('PREFEITURA_CNPJ', 'XX.XXX.XXX/0001-XX'),
            'email' => env('PREFEITURA_EMAIL', 'contato@email.com'),
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.candidatos.relatorios.pdf-template', [
            'candidatos' => $candidatos,
            'appliedFilters' => $appliedFilters,
            'dataGeracao' => now()->format('d/m/Y H:i:s'),
            'prefeituraInfo' => $prefeituraInfo
        ]);
        
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio_candidatos_' . now()->format('Y-m-d') . '.pdf');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Erro ao gerar PDF de relatório: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Não foi possível gerar o PDF. Verifique os logs.');
    }
}

/**
 * Gera um PDF com o perfil completo de um candidato específico.
 */
public function exportarPerfilPdf(Candidato $candidato)
{
    try {
        $candidato->load(['user', 'curso', 'instituicao', 'documentos', 'atividades.tipoDeAtividade']);

        $prefeituraInfo = [
            'nome' => env('PREFEITURA_NOME', 'Portal do Estagiário'),
            'endereco' => env('PREFEITURA_ENDERECO', 'Endereço da Prefeitura, Cidade, UF'),
            'telefone' => env('PREFEITURA_TELEFONE', '(00) 00000-0000'),
            'cnpj' => env('PREFEITURA_CNPJ', 'XX.XXX.XXX/0001-XX'),
            'email' => env('PREFEITURA_EMAIL', 'contato@email.com'),
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.candidatos.perfil-pdf-template', [
            'candidato' => $candidato,
            'prefeituraInfo' => $prefeituraInfo,
            'dataGeracao' => now()->format('d/m/Y H:i:s')
        ]);
        
        return $pdf->stream('Perfil - ' . $candidato->nome_completo . '.pdf');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Erro ao gerar PDF de perfil: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Não foi possível gerar o PDF do perfil. Verifique os logs.');
    }
}

    public function ranking()
    {
        $candidatosHomologados = Candidato::where('status', 'Homologado')
            ->with('curso')
            ->get()
            ->map(function($candidato) {
                $pontuacao = $candidato->calcularPontuacaoDetalhada();
                $candidato->pontuacao_final = $pontuacao['total'];
                return $candidato;
            })
            ->sortByDesc('pontuacao_final');

        $candidatosPorCurso = $candidatosHomologados->groupBy('curso.nome');

        return view('admin.candidatos.ranking', compact('candidatosPorCurso'));
    }

    public function showAtribuirVagaForm(Candidato $candidato)
    {
        if ($candidato->status !== 'Homologado') {
            return redirect()->route('admin.candidatos.ranking')->with('error', 'Este candidato não está no status "Homologado" e não pode ser convocado.');
        }
        return view('admin.candidatos.atribuir-vaga', compact('candidato'));
    }

    public function convocar(Request $request, Candidato $candidato)
    {
        $validatedData = $request->validate([
            'lotacao_local' => 'required|string|max:255',
            'lotacao_chefia' => 'required|string|max:255',
            'contrato_data_inicio' => 'required|date',
            'contrato_data_fim' => 'required|date|after_or_equal:contrato_data_inicio',
            'prorrogacao_data_inicio' => 'nullable|date',
            'prorrogacao_data_fim' => 'nullable|date|after_or_equal:prorrogacao_data_inicio',
            'lotacao_observacoes' => 'nullable|string',
        ]);

        if ($candidato->status !== 'Homologado') {
            return redirect()->route('admin.candidatos.ranking')->with('error', 'Este candidato não está mais disponível para convocação.');
        }

        try {
            $candidato->status = 'Convocado';
            $candidato->convocado_em = now();
            $candidato->fill($validatedData);
            $candidato->save();

            return redirect()->route('admin.candidatos.ranking')->with('success', "Candidato {$candidato->nome_completo} convocado com sucesso!");

        } catch (\Exception $e) {
            Log::error("Erro ao convocar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar a convocação.');
        }
    }

    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('admin.candidatos.create', compact('cursos', 'instituicoes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:candidatos,cpf',
        ], [
            'user_id.required' => 'O usuário associado é obrigatório.',
            'user_id.exists' => 'O usuário associado não existe.',
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ]);

        $validatedData['status'] = 'Inscrição Incompleta'; 

        try {
            Candidato::create($validatedData);
            return redirect()->route('admin.candidatos.index')->with('success', 'Candidato criado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao criar candidato: " . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o candidato.')->withInput();
        }
    }

    public function show(Candidato $candidato)
    {
        $candidato->load([
            'documentos', 
            'atividades.tipoDeAtividade', 
            'curso',
            'instituicao'
        ]);

        $documentosNecessarios = [
            'HISTORICO_ESCOLAR' => 'Histórico Escolar',
            'DECLARACAO_MATRICULA' => 'Declaração de Matrícula',
            'DECLARACAO_ELEITORAL' => 'Declaração de Quitação Eleitoral',
        ];

        if ($candidato->sexo === 'Masculino') {
            $documentosNecessarios['RESERVISTA'] = 'Comprovante de Reservista';
        }
        if ($candidato->possui_deficiencia) {
            $documentosNecessarios['LAUDO_MEDICO'] = 'Laudo Médico (PCD)';
        }

        $documentosEnviados = $candidato->documentos->keyBy('tipo_documento');

        $pontuacaoDetalhada = method_exists($candidato, 'calcularPontuacaoDetalhada') 
            ? $candidato->calcularPontuacaoDetalhada() 
            : ['total' => 0, 'detalhes' => []];

        return view('admin.candidatos.show', [
            'candidato' => $candidato,
            'pontuacaoTotal' => $pontuacaoDetalhada['total'],
            'detalhesPontuacao' => $pontuacaoDetalhada['detalhes'],
            'documentosNecessarios' => $documentosNecessarios,
            'documentosEnviados' => $documentosEnviados,
        ]);
    }

    public function edit(Candidato $candidato)
    {
        $cursos = Curso::orderBy('nome')->get();
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('admin.candidatos.edit', compact('candidato', 'cursos', 'instituicoes'));
    }

    public function update(Request $request, Candidato $candidato)
    {
        if ($request->input('status') === 'Aprovado') {
            $prazosAtivos = $candidato->atividades()
                ->where('status', 'Rejeitada')
                ->where('prazo_recurso_ate', '>', now())
                ->exists();

            if ($prazosAtivos) {
                return redirect()->back()->with('error', 'Não é possível aprovar. O candidato possui atividades com prazo de recurso em andamento.');
            }
        }
        
        $validatedData = $request->validate([
            'status' => 'required|in:Em Análise,Aprovado,Rejeitado,Inscrição Incompleta',
            'admin_observacao' => 'nullable|string',
        ]);

        $novoStatus = $validatedData['status'];

        if ($novoStatus === 'Rejeitado') {
            $candidato->status = 'Inscrição Incompleta';
            Log::info("Admin rejeitou a inscrição do candidato ID {$candidato->id}. Status movido para 'Inscrição Incompleta'.");
        } else {
            $candidato->status = $novoStatus;
        }
        
        $candidato->admin_observacao = $validatedData['admin_observacao'];
        
        if ($candidato->status === 'Aprovado') {
            $candidato->revert_reason = null;
        }
        
        try {
            $candidato->save();
            return redirect()->route('admin.candidatos.show', $candidato)->with('success', 'Status do candidato atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o candidato.');
        }
    }

    public function destroy(Candidato $candidato)
    {
        DB::beginTransaction();
        try {
            $user = $candidato->user;
            $candidato->delete();
            
            if ($user) {
                $user->delete();
            }

            DB::commit();
            
            return redirect()->route('admin.candidatos.index')->with('success', 'Candidato e todos os seus dados foram apagados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao apagar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o candidato.');
        }
    }

    public function updateDocumentStatus(Request $request, Documento $documento)
    {
        // Validação corrigida (removida a duplicata)
        $validated = $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
            'motivo_rejeicao' => 'required_if:status,rejeitado|nullable|string|min:10',
        ]);
        
        if ($documento->status === 'rejeitado' && $validated['status'] === 'aprovado') {
            return back()->with('error', 'Documento rejeitado não pode ser aprovado. O candidato deve reenviar o documento corrigido.');
        }

        DB::beginTransaction();
        try {
            $documento->status = $validated['status'];

            if ($documento->status === 'rejeitado') {
                $documento->motivo_rejeicao = $validated['motivo_rejeicao'];
                
                $candidato = $documento->candidato;
                if ($candidato) {
                    $candidato->status = 'Inscrição Incompleta';
                    $candidato->admin_observacao = "A Comissão Organizadora solicitou correções. Verifique o motivo em cada item rejeitado e reenvie os documentos necessários.";
                    $candidato->save();
                }
            } else {
                $documento->motivo_rejeicao = null;
                
                $candidato = $documento->candidato;
                if ($candidato && $candidato->status === 'Inscrição Incompleta') {
                    
                    $documentosObrigatorios = ['HISTORICO_ESCOLAR', 'DECLARACAO_MATRICULA', 'DECLARACAO_ELEITORAL'];
                    if ($candidato->sexo === 'Masculino') {
                        $documentosObrigatorios[] = 'RESERVISTA';
                    }
                    if ($candidato->possui_deficiencia) {
                        $documentosObrigatorios[] = 'LAUDO_MEDICO';
                    }
                    
                    $todosAprovados = true;
                    foreach ($documentosObrigatorios as $tipo) {
                        $doc = $candidato->documentos()->where('tipo_documento', $tipo)->first();
                        if (!$doc || $doc->status !== 'aprovado') {
                            $todosAprovados = false;
                            break;
                        }
                    }
                    
                    if ($todosAprovados) {
                        $candidato->status = 'Em Análise';
                        $candidato->admin_observacao = null;
                        $candidato->save();
                        Log::info("Candidato ID {$candidato->id} voltou para 'Em Análise' - todos os documentos aprovados.");
                    }
                }
            }
            
            $documento->save();
            DB::commit();

            return back()->with('success', 'Status do documento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar status do documento ID {$documento->id}: " . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao atualizar o status do documento.');
        }
    }

    public function homologar(Request $request, Candidato $candidato)
    {
        $prazosAtivos = $candidato->atividades()
            ->where('status', 'Rejeitada')
            ->where('prazo_recurso_ate', '>', now())
            ->exists();

        if ($prazosAtivos) {
            return redirect()->back()->with('error', 'Não é possível homologar. O candidato possui atividades com prazo de recurso em andamento.');
        }

        $request->validate([
            'ato_homologacao' => 'required|string|max:255',
            'homologacao_observacoes' => 'nullable|string',
        ], [
            'ato_homologacao.required' => 'O campo "Número/Referência do Ato de Homologação" é obrigatório.',
        ]);

        if ($candidato->status !== 'Aprovado') {
            return redirect()->back()->with('error', 'Apenas candidatos "Aprovados" podem ser homologados.');
        }

        try {
            $candidato->status = 'Homologado';
            $candidato->ato_homologacao = $request->input('ato_homologacao');
            $candidato->homologado_em = now();
            $candidato->homologacao_observacoes = $request->input('homologacao_observacoes');
            $candidato->revert_reason = null;
            
            $candidato->recurso_prazo_ate = $this->calcularDiasUteis(2);
            $candidato->recurso_status = 'pendente';
            $candidato->recurso_tipo = 'classificacao';

            $candidato->save();
            return redirect()->back()->with('success', 'Candidato homologado com sucesso! O prazo para recurso de classificação foi aberto.');
        } catch (\Exception $e) {
            Log::error("Erro ao homologar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao homologar o candidato.');
        }
    }

    public function deferirRecurso(Request $request, Candidato $candidato, $recurso_index)
    {
        $historico = $candidato->recurso_historico ?? [];

        if (!isset($historico[$recurso_index])) {
            return back()->with('error', 'Recurso não encontrado no histórico.');
        }

        if (!empty($historico[$recurso_index]['decisao_admin'])) {
            return back()->with('error', 'Este recurso já foi decidido.');
        }

        $historico[$recurso_index]['decisao_admin'] = 'deferido';
        $historico[$recurso_index]['justificativa_admin'] = $request->input('justificativa_admin');
        $historico[$recurso_index]['data_decisao_admin'] = now()->toDateTimeString();
        $historico[$recurso_index]['admin_id'] = auth()->id();

        $candidato->recurso_historico = $historico;
        
        $candidato->save();

        return redirect()->route('admin.candidatos.show', $candidato)->with('success', 'Recurso deferido com sucesso! Lembre-se de reavaliar os itens do candidato.');
    }

    public function indeferirRecurso(Request $request, Candidato $candidato, $recurso_index)
    {
        $request->validate(['justificativa_admin' => 'required|string|min:10'], 
        ['justificativa_admin.required' => 'A justificativa para indeferir o recurso é obrigatória.']);

        $historico = $candidato->recurso_historico ?? [];

        if (!isset($historico[$recurso_index])) {
            return back()->with('error', 'Recurso não encontrado no histórico.');
        }
        
        if (!empty($historico[$recurso_index]['decisao_admin'])) {
            return back()->with('error', 'Este recurso já foi decidido.');
        }

        $historico[$recurso_index]['decisao_admin'] = 'indeferido';
        $historico[$recurso_index]['justificativa_admin'] = $request->input('justificativa_admin');
        $historico[$recurso_index]['data_decisao_admin'] = now()->toDateTimeString();
        $historico[$recurso_index]['admin_id'] = auth()->id();

        $candidato->recurso_historico = $historico;
        $candidato->save();

        return redirect()->route('admin.candidatos.show', $candidato)->with('success', 'Recurso indeferido com sucesso.');
    }

    private function calcularDiasUteis(int $diasUteisParaAdicionar): Carbon
    {
        $data = Carbon::now();
        $diasAdicionados = 0;
        while ($diasAdicionados < $diasUteisParaAdicionar) {
            $data->addDay();
            if ($data->isWeekday()) {
                $diasAdicionados++;
            }
        }
        return $data->setTime(17, 0, 0); 
    }
}