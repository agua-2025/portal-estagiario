<x-app-layout>
    {{-- ✅ AJUSTE: Adiciona variáveis para o novo modal de rejeição de documentos --}}
    <div class="py-12"> {{-- Fundo da seção principal voltou ao padrão de x-app-layout (geralmente branco ou padrão) --}}
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-800">

                    {{-- CABEÇALHO COM STATUS --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b border-gray-200 pb-4">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 leading-tight">{{ $candidato->nome_completo ?? $candidato->user->name }}</h2>
                            <p class="text-sm text-gray-500 mt-1">Inscrição recebida em: {{ $candidato->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-center gap-4">
                            @php
                                $statusClass = 'bg-gray-100 text-gray-700'; // Default
                                $statusText = $candidato->status;

                                if ($candidato->status === 'Inscrição Incompleta') {
                                    $statusClass = 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200';
                                } elseif ($candidato->status === 'Em Análise') {
                                    $statusClass = 'bg-blue-100 text-blue-800 ring-1 ring-blue-200';
                                } elseif ($candidato->status === 'Aprovado') {
                                    $statusClass = 'bg-green-100 text-green-800 ring-1 ring-green-200';
                                } elseif ($candidato->status === 'Homologado') {
                                    $statusClass = 'bg-purple-100 text-purple-800 ring-1 ring-purple-200';
                                } elseif ($candidato->status === 'Rejeitado') {
                                    $statusClass = 'bg-red-100 text-red-800 ring-1 ring-red-200';
                                }
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                            <a href="{{ route('admin.candidatos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition ease-in-out duration-150">
                                Voltar
                            </a>
                        </div>
                    </div>

                    {{-- LÓGICA DE VERIFICAÇÃO DE ALTERAÇÕES E PRAZOS --}}
                    @php
                        $profile_was_updated = false;
                        $changed_document_types = [];

                        if($candidato->revert_reason && is_array($candidato->revert_reason) && !empty($candidato->revert_reason)) {
                            $history = $candidato->revert_reason;
                            $changes_to_review = [];
                            $reversed_history = array_reverse($history);
                            foreach ($reversed_history as $event) {
                                $changes_to_review[] = $event;
                                if (in_array($event['previous_status'], ['Homologado', 'Aprovado'])) {
                                    break;
                                }
                            }
                            
                            foreach ($changes_to_review as $change) {
                                if ($change['action'] === 'profile_update') {
                                    $profile_was_updated = true;
                                }
                                if (in_array($change['action'], ['document_update', 'document_delete'])) {
                                    if (!in_array($change['document_type'], $changed_document_types)) {
                                        $changed_document_types[] = $change['document_type'];
                                    }
                                }
                            }
                        }

                        $prazosAtivos = $candidato->user->candidatoAtividades()
                                                ->where('status', 'Rejeitada')
                                                ->where('prazo_recurso_ate', '>', now())
                                                ->exists();
                    @endphp

                    {{-- Alerta GLOBAL se o perfil foi alterado --}}
                    @if($profile_was_updated)
                        <div class="mb-6 p-4 border-l-4 border-yellow-400 bg-yellow-50 text-yellow-800 rounded-lg text-sm" role="alert">
                            <p><span class="font-bold">Atenção:</span> As informações do perfil do candidato foram alteradas recentemente e precisam de reanálise.</p>
                        </div>
                    @endif

                    {{-- PLACAR DE PONTUAÇÃO --}}
                    <div class="mb-6 p-5 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-blue-700">Pontuação Total (Itens Aprovados)</h3>
                                <p class="mt-1 text-4xl font-extrabold text-blue-900">{{ number_format($pontuacaoTotal, 2, ',', '.') }} pontos</p>
                            </div>
                            <button @click="showScoreDetails = !showScoreDetails" class="text-sm text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
                                <span x-show="!showScoreDetails">Ver Detalhes</span>
                                <span x-show="showScoreDetails">Esconder Detalhes</span>
                            </button>
                        </div>
                        
                        <div x-show="showScoreDetails" x-transition.opacity class="mt-4 pt-4 border-t border-blue-200">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase mb-3">Extrato de Pontos</h4>
                            <table class="min-w-full text-sm">
                                <tbody class="divide-y divide-blue-100">
                                    @forelse($detalhesPontuacao as $detalhe)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                                        <td class="py-2 pr-4 text-gray-700">{{ $detalhe['nome'] }}</td>
                                        <td class="py-2 pl-4 text-right font-semibold text-gray-900">{{ number_format($detalhe['pontos'], 2, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="py-2 text-gray-500 italic">Nenhuma atividade foi aprovada ainda.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- NOVA NAVEGAÇÃO POR ABAS --}}
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                            <button @click="tab = 'perfil'" :class="{ 'border-blue-600 text-blue-700 font-semibold': tab === 'perfil', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'perfil' }" class="whitespace-nowrap py-3 px-1 border-b-2 text-sm focus:outline-none transition-colors duration-200">
                                Perfil do Candidato
                            </button>
                            <button @click="tab = 'analise'" :class="{ 'border-blue-600 text-blue-700 font-semibold': tab === 'analise', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'analise' }" class="whitespace-nowrap py-3 px-1 border-b-2 text-sm focus:outline-none transition-colors duration-200">
                                Análise de Documentos
                            </button>
                            <button @click="tab = 'acoes'" :class="{ 'border-blue-600 text-blue-700 font-semibold': tab === 'acoes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'acoes' }" class="whitespace-nowrap py-3 px-1 border-b-2 text-sm focus:outline-none transition-colors duration-200">
                                Ações Finais
                            </button>
                        </nav>
                    </div>

                    {{-- CONTEÚDO DAS ABAS --}}
                    
                    {{-- Aba 1: Perfil do Candidato --}}
                    <div x-show="tab === 'perfil'" x-transition.opacity>
                        @php
                        function renderDetail($label, $value) {
                            if (empty($value) && !is_numeric($value)) return;
                            echo '<div class="mb-4">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">' . $label . '</h4>
                                    <p class="mt-1 text-base text-gray-900 font-medium">' . e($value) . '</p>
                                  </div>';
                        }
                        @endphp
                        <div class="mt-6 p-4 border border-gray-100 rounded-lg bg-white shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 mb-5">Dados Pessoais</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                                {{ renderDetail('Nome da Mãe', $candidato->nome_mae) }}
                                {{ renderDetail('Nome do Pai', $candidato->nome_pai) }}
                                {{ renderDetail('Data de Nascimento', optional($candidato->data_nascimento)->format('d/m/Y')) }}
                                {{ renderDetail('Sexo', $candidato->sexo) }}
                                {{ renderDetail('CPF', $candidato->cpf) }}
                                {{ renderDetail('RG', $candidato->rg) }}
                                {{ renderDetail('Órgão Expedidor', $candidato->rg_orgao_expedidor) }}
                                {{ renderDetail('Telefone', $candidato->telefone) }}
                                {{ renderDetail('Possui Deficiência?', $candidato->possui_deficiencia ? 'Sim' : 'Não') }}
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-200 p-4 border border-gray-100 rounded-lg bg-white shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 mb-5">Dados Acadêmicos</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                               {{ renderDetail('Instituição de Ensino', $candidato->instituicao->nome ?? 'N/A') }}
                                {{ renderDetail('Curso', $candidato->curso->nome ?? 'N/A') }}
                                {{ renderDetail('Início do Curso', optional($candidato->curso_data_inicio)->format('d/m/Y')) }}
                                {{ renderDetail('Previsão de Conclusão', optional($candidato->curso_previsao_conclusao)->format('d/m/Y')) }}
                                {{ renderDetail('Média de Aproveitamento', $candidato->media_aproveitamento) }}
                                {{ renderDetail('Semestres Concluídos', $candidato->semestres_completos) }}
                            </div>
                        </div>
                    </div>

                    {{-- Aba 2: Análise de Documentos --}}
                    <div x-show="tab === 'analise'" x-transition.opacity style="display: none;">
                        <div class="space-y-5">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Documentos e Atividades Enviadas</h3>
                            
                            @foreach($documentosNecessarios as $tipoDocumento => $nomeDocumento)
                                @php
                                    $documentoEnviado = $documentosEnviados->get($tipoDocumento);
                                @endphp
                                <div class="p-5 border border-gray-200 rounded-lg flex flex-col sm:flex-row justify-between items-start gap-4 text-sm bg-white shadow-sm">
                                    {{-- Informações do Documento --}}
                                    <div class="flex-grow">
                                        {{-- ✅ AJUSTE: Mover status para o topo, antes do título, e garantir consistência com atividades --}}
                                        @if($documentoEnviado)
                                            <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full mb-1 inline-block
                                                @if($documentoEnviado->status == 'aprovado') bg-green-100 text-green-700 ring-1 ring-green-200 @endif
                                                @if($documentoEnviado->status == 'enviado') bg-blue-100 text-blue-700 ring-1 ring-blue-200 @endif
                                                @if($documentoEnviado->status == 'rejeitado') bg-red-100 text-red-700 ring-1 ring-red-200 @endif
                                            ">
                                                Status: {{ $documentoEnviado->status }}
                                            </span>
                                        @else
                                            <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 ring-1 ring-gray-200 mb-1 inline-block">Status: Pendente</span>
                                        @endif

                                        <div class="flex items-center"> {{-- Removida a classe mb-1 que pode causar espaçamento duplo --}}
                                            <p class="font-bold text-gray-800 text-base">{{ $nomeDocumento }}</p>
                                            @if(in_array($tipoDocumento, $changed_document_types) && $documentoEnviado && $documentoEnviado->status !== 'aprovado')
                                                <span class="ml-3 px-2.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold ring-1 ring-yellow-200">ALTERADO</span>
                                            @endif
                                        </div>
                                        @if($documentoEnviado && $documentoEnviado->status == 'rejeitado' && $documentoEnviado->motivo_rejeicao)
                                            <p class="text-xs text-red-600 mt-2">Motivo: {{ $documentoEnviado->motivo_rejeicao }}</p>
                                        @endif
                                    </div>
                                    
                                    {{-- Ações do Admin para o Documento --}}
                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 flex-shrink-0 min-w-[200px] sm:min-w-[unset]"> {{-- min-w para garantir que os botões não quebrem em mobile --}}
                                        @if($documentoEnviado)
                                            <a href="{{ route('candidato.documentos.show', $documentoEnviado) }}" target="_blank" class="w-full sm:w-28 px-4 py-2 bg-gray-500 text-white rounded-md text-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 text-center">Visualizar</a> {{-- Cor mais suave: gray-500 --}}
                                            
                                            @if($documentoEnviado->status !== 'aprovado')
                                                <form action="{{ route('admin.documentos.updateStatus', $documentoEnviado) }}" method="POST" onsubmit="return confirm('Aprovar este documento?');" class="w-full sm:w-28">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="aprovado">
                                                    <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-md text-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition-colors duration-200">Aprovar</button> {{-- Cor mais suave: green-500 --}}
                                                </form>
                                            @endif

                                            @if($documentoEnviado->status !== 'rejeitado')
                                                <button @click="showDocRejectionModal = true; docRejectionAction = '{{ route('admin.documentos.updateStatus', $documentoEnviado) }}'" type="button" class="w-full sm:w-28 px-4 py-2 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-colors duration-200">Rejeitar</button> {{-- Cor mais suave: red-500 --}}
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-500 italic w-full sm:w-auto text-center sm:text-left">Aguardando envio do candidato.</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @foreach($candidato->user->candidatoAtividades as $atividade)
                                <div class="p-5 border border-gray-200 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm bg-white shadow-sm">
                                    <div class="flex-grow mb-3 sm:mb-0">
                                        {{-- ✅ AJUSTE: Mover status para o topo, antes do título, e garantir consistência com documentos --}}
                                        @php
                                            $statusClassAtividade = 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200';
                                            if ($atividade->status === 'Aprovada') $statusClassAtividade = 'bg-green-100 text-green-800 ring-1 ring-green-200';
                                            elseif ($atividade->status === 'Rejeitada') $statusClassAtividade = 'bg-red-100 text-red-800 ring-1 ring-red-200';
                                            elseif ($atividade->status === 'enviado') $statusClassAtividade = 'bg-blue-100 text-blue-800 ring-1 ring-blue-200';
                                            elseif ($atividade->status === 'Em Análise') $statusClassAtividade = 'bg-purple-100 text-purple-800 ring-1 ring-purple-200';
                                        @endphp
                                        <span class="font-medium capitalize px-2.5 py-1 rounded-full text-xs mr-3 {{ $statusClassAtividade }} mb-1 inline-block">{{ $atividade->status }}</span>

                                        <p class="font-bold text-gray-800 text-base">{{ $atividade->tipoDeAtividade->nome ?? 'Regra não encontrada' }}</p>
                                        <p class="text-xs text-gray-600 mt-1 ml-4">{{ $atividade->descricao_customizada }}</p>
                                        
                                        <div class="mt-2 ml-4 pl-3 border-l-2 border-gray-200 text-xs text-gray-800">
                                            @if (str_contains(strtolower($atividade->tipoDeAtividade->nome), 'aproveitamento acadêmico'))
                                                <p><strong>Média Declarada no Perfil:</strong> {{ $candidato->media_aproveitamento ?? 'N/A' }}</p>
                                            @elseif($atividade->tipoDeAtividade->unidade_medida === 'horas')
                                                <p><strong>Horas Declaradas:</strong> {{ $atividade->carga_horaria ?? 'N/A' }}</p>
                                            @elseif($atividade->tipoDeAtividade->unidade_medida === 'meses')
                                                <p><strong>Período Declarado:</strong> de {{ optional($atividade->data_inicio)->format('d/m/Y') ?? 'N/A' }} a {{ optional($atividade->data_fim)->format('d/m/Y') ?? 'N/A' }}</p>
                                            @elseif(str_contains(strtolower($atividade->tipoDeAtividade->nome), 'semestres cursados') || $atividade->tipoDeAtividade->unidade_medida === 'semestre')
                                                <p><strong>Semestres Declarados na Atividade:</strong> {{ $atividade->semestres_declarados ?? 'N/A' }}</p>
                                            @endif
                                        </div>

                                        @if($atividade->status === 'Rejeitada' && $atividade->motivo_rejeicao)
                                            <div class="text-xs text-red-600 mt-2 p-3 bg-red-50 rounded-md border border-red-100">
                                                <p class="font-semibold">Motivo da Rejeição:</p>
                                                <p class="mt-1">{{ $atividade->motivo_rejeicao }}</p>
                                                @if($atividade->prazo_recurso_ate)
                                                    @if(\Carbon\Carbon::now()->lt($atividade->prazo_recurso_ate))
                                                        <p class="mt-2 text-blue-700 font-medium">
                                                            Prazo para Recurso: {{ \Carbon\Carbon::parse($atividade->prazo_recurso_ate)->format('d/m/Y H:i') }}
                                                        </p>
                                                    @else
                                                        <p class="mt-2 text-gray-600 italic">Prazo para Recurso Encerrado.</p>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 flex-shrink-0 min-w-[200px] sm:min-w-[unset]"> {{-- Largura fixa em desktop --}}
                                        <a href="{{ route('candidato.atividades.show', $atividade) }}" target="_blank" class="w-full sm:w-28 px-4 py-2 bg-gray-500 text-white rounded-md text-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 text-center">Visualizar</a>
                                        
                                        @if ($atividade->status !== 'Aprovada')
                                            <form action="{{ route('admin.atividades.aprovar', $atividade->id) }}" method="POST" onsubmit="return confirm('Aprovar este item?');" class="w-full sm:w-28">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-md text-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition-colors duration-200">Aprovar Item</button>
                                            </form>
                                        @endif

                                        @if ($atividade->status !== 'Rejeitada')
                                            <button @click="showRejectionModal = true; rejectionAction = '{{ route('admin.atividades.rejeitar', $atividade->id) }}'" type="button" class="w-full sm:w-28 px-4 py-2 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-colors duration-200">Rejeitar Item</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Aba 3: Ações Finais --}}
                    <div x-show="tab === 'acoes'" x-transition.opacity x-cloak>
                        <div class="mt-8 pt-6 border-t border-gray-200 p-6 bg-white rounded-lg shadow-sm">
                           <h3 class="text-xl font-semibold text-gray-800 mb-3">Painel de Ações do Administrador</h3>
                            <p class="text-sm text-gray-600 mb-6">Após analisar todas as informações, use os botões abaixo para alterar o status da inscrição.</p>
                            
                            @if($prazosAtivos)
                                <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg border border-yellow-200">
                                    <p class="font-bold">Ações Finais Bloqueadas</p>
                                    <p class="text-sm mt-1">O candidato possui uma ou mais atividades com prazo de recurso em andamento. Aguarde o término do prazo para prosseguir.</p>
                                </div>
                            @else
                                @if ($candidato->status === 'Em Análise')
                                    <p class="text-base text-gray-700 mb-5">O candidato está aguardando sua análise. Por favor, revise o perfil e os documentos/atividades antes de tomar uma ação.</p>

                                    <form action="{{ route('admin.candidatos.update', $candidato->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-6">
                                            <div>
                                                <label for="admin_observacao" class="block text-sm font-medium text-gray-700 mb-1">Justificativa / Observação</label>
                                                <textarea name="admin_observacao" id="admin_observacao" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">{{ $candidato->admin_observacao }}</textarea>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-4">
                                                <button type="submit" name="status" value="Aprovado" class="px-6 py-2 bg-green-500 text-white rounded-lg text-base font-semibold hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition-colors duration-200">Aprovar Inscrição</button> {{-- Cor mais suave: green-500 --}}
                                                <button @click="showProfileRejectionModal = true" type="button" class="px-6 py-2 bg-red-500 text-white rounded-lg text-base font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-colors duration-200">Rejeitar Inscrição</button> {{-- Cor mais suave: red-500 --}}
                                            </div>
                                        </div>
                                    </form>
                                @elseif ($candidato->status === 'Aprovado')
                                    <form action="{{ route('admin.candidatos.homologar', $candidato->id) }}" method="POST" class="w-full mt-4">
                                        @csrf
                                        <div class="bg-blue-50 p-5 rounded-lg mt-4 mb-4 border border-blue-200 shadow-sm">
                                            <p class="font-bold text-blue-800 text-lg mb-3">Ação: Homologar Candidato</p>
                                            <div class="mb-4">
                                                <label for="ato_homologacao" class="block text-sm font-medium text-gray-700 mb-1">Número/Referência do Ato de Homologação <span class="text-red-500">*</span></label>
                                                <input type="text" name="ato_homologacao" id="ato_homologacao" required 
                                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out"
                                                        value="{{ old('ato_homologacao', $candidato->ato_homologacao) }}">
                                                @error('ato_homologacao')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <label for="homologacao_observacoes" class="block text-sm font-medium text-gray-700 mb-1">Observações (Opcional)</label>
                                                <textarea name="homologacao_observacoes" id="homologacao_observacoes" rows="4" 
                                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">{{ old('homologacao_observacoes', $candidato->homologacao_observacoes) }}</textarea>
                                                @error('homologacao_observacoes')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="submit" class="w-full px-6 py-2.5 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-semibold text-base focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200">Homologar Candidato</button>
                                        </div>
                                    </form>
                                @elseif ($candidato->status === 'Homologado')
                                    <div class="bg-blue-50 p-5 rounded-lg mt-4 mb-4 border border-blue-200 shadow-sm">
                                        <p class="font-bold text-blue-800 text-lg mb-3">Candidato Homologado!</p>
                                        <p class="text-sm text-blue-700 mb-1">Ato de Homologação: <span class="font-medium">{{ $candidato->ato_homologacao ?? 'N/A' }}</span></p>
                                        <p class="text-sm text-blue-700">Homologado em: <span class="font-medium">{{ $candidato->homologado_em ? $candidato->homologado_em->format('d/m/Y H:i') : 'N/A' }}</span></p>
                                        @if ($candidato->homologacao_observacoes)
                                            <p class="text-sm text-blue-700 mt-2">Observações: <span class="font-medium">{{ $candidato->homologacao_observacoes }}</span></p>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-gray-100 p-5 rounded-lg mt-4 mb-4 border border-gray-200 shadow-sm">
                                        <p class="font-bold text-gray-800 text-lg mb-2">Ações Atuais:</p>
                                        <p class="text-base text-gray-700">O status do candidato não permite homologação ou aprovação direta no momento. Por favor, verifique o status atual e as atividades pendentes.</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAIS --}}
        {{-- Modal de Rejeição de Atividade --}}
        <div x-show="showRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 p-4" style="display: none;">
            <div @click.away="showRejectionModal = false" class="bg-white rounded-xl shadow-2xl p-7 w-full max-w-lg mx-auto">
                <h3 class="text-2xl font-bold text-gray-800 mb-5">Justificar Rejeição</h3>
                <form :action="rejectionAction" method="POST">
                    @csrf
                    <div>
                        <label for="motivo_rejeicao" class="block text-sm font-medium text-gray-700 mb-2">Por favor, descreva o motivo da rejeição:</label>
                        <textarea name="motivo_rejeicao" id="motivo_rejeicao" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition duration-150 ease-in-out" required minlength="10" placeholder="Ex: Documento ilegível, dados inconsistentes, etc."></textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="showRejectionModal = false" type="button" class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-colors duration-200">Confirmar Rejeição</button> {{-- Cor mais suave: red-500 --}}
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal de Rejeição de Perfil --}}
        <div x-show="showProfileRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 p-4" style="display: none;">
            <div @click.away="showProfileRejectionModal = false" class="bg-white rounded-xl shadow-2xl p-7 w-full max-w-lg mx-auto">
                <h3 class="text-2xl font-bold text-gray-800 mb-5">Rejeitar Inscrição do Candidato</h3>
                <form action="{{ route('admin.candidatos.update', $candidato->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="Rejeitado">
                    <div>
                        <label for="profile_motivo_rejeicao" class="block text-sm font-medium text-gray-700 mb-2">Por favor, descreva o motivo da rejeição da inscrição:</label>
                        <textarea name="admin_observacao" id="profile_motivo_rejeicao" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition duration-150 ease-in-out" required minlength="10" placeholder="Ex: Perfil incompleto, inconsistências nos dados pessoais, etc."></textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="showProfileRejectionModal = false" type="button" class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-colors duration-200">Confirmar Rejeição da Inscrição</button> {{-- Cor mais suave: red-500 --}}
                    </div>
                </form>
            </div>
        </div>

        {{-- ✅ NOVO MODAL: Para rejeição de documentos individuais --}}
        <div x-show="showDocRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 p-4" style="display: none;">
            <div @click.away="showDocRejectionModal = false" class="bg-white rounded-xl shadow-2xl p-7 w-full max-w-lg mx-auto">
                <h3 class="text-2xl font-bold text-gray-800 mb-5">Justificar Rejeição do Documento</h3>
                <form :action="docRejectionAction" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejeitado">
                    <div>
                        <label for="doc_motivo_rejeicao" class="block text-sm font-medium text-gray-700 mb-2">Por favor, descreva o motivo da rejeição:</label>
                        <textarea name="motivo_rejeicao" id="doc_motivo_rejeicao" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition duration-150 ease-in-out" required minlength="10" placeholder="Ex: Conteúdo ilegível, documento incorreto, etc."></textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="showDocRejectionModal = false" type="button" class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-colors duration-200">Confirmar Rejeição</button> {{-- Cor mais suave: red-500 --}}
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>