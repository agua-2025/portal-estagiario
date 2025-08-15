<x-app-layout>
    {{-- ✅ AJUSTE: Adiciona variáveis para os novos modais de recurso --}}
    <div class="py-12" x-data="{ tab: 'acoes', showRejectionModal: false, rejectionAction: '', showScoreDetails: false, showProfileRejectionModal: false, showDocRejectionModal: false, docRejectionAction: '', showResourceDenialModal: false, resourceDenialAction: '', showResourceApprovalModal: false, resourceApprovalAction: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- CABEÇALHO COM STATUS --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $candidato->nome_completo ?? $candidato->user->name }}</h2>
                            <p class="text-sm text-gray-500">Inscrição recebida em: {{ $candidato->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-center gap-4">
                            @php
                                $statusClass = 'bg-gray-100 text-gray-800'; // Default
                                $statusText = $candidato->status;

                                if ($candidato->status === 'Inscrição Incompleta') {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($candidato->status === 'Em Análise') {
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                } elseif ($candidato->status === 'Aprovado') {
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($candidato->status === 'Homologado') {
                                    $statusClass = 'bg-purple-100 text-purple-800';
                                } elseif ($candidato->status === 'Rejeitado') {
                                    $statusClass = 'bg-red-100 text-red-800';
                                }
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                            <a href="{{ route('admin.candidatos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
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

                        $prazosAtivos = $candidato->atividades()
                                                ->where('status', 'Rejeitada')
                                                ->where('prazo_recurso_ate', '>', now())
                                                ->exists();
                    @endphp

                    {{-- Alerta GLOBAL se o perfil foi alterado --}}
                    @if($profile_was_updated)
                        <div class="mb-6 p-4 border-l-4 border-yellow-500 bg-yellow-100 text-yellow-800 rounded-lg text-sm" role="alert">
                            <p><span class="font-bold">Atenção:</span> As informações do perfil do candidato foram alteradas recentemente e precisam de reanálise.</p>
                        </div>
                    @endif

                    {{-- PLACAR DE PONTUAÇÃO --}}
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-blue-800">Pontuação Total (Itens Aprovados)</h3>
                                <p class="mt-1 text-3xl font-bold text-blue-900">{{ number_format($pontuacaoTotal, 2, ',', '.') }} pontos</p>
                            </div>
                            <button @click="showScoreDetails = !showScoreDetails" class="text-sm text-blue-600 hover:underline">
                                <span x-show="!showScoreDetails">Ver Detalhes</span>
                                <span x-show="showScoreDetails">Esconder Detalhes</span>
                            </button>
                        </div>
                        
                        <div x-show="showScoreDetails" x-transition class="mt-4 pt-4 border-t border-blue-200">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase mb-2">Extrato de Pontos</h4>
                            <table class="min-w-full text-sm">
                                <tbody>
                                    @forelse($detalhesPontuacao as $detalhe)
                                    <tr class="border-b border-blue-100 last:border-b-0">
                                        <td class="py-2 pr-4 text-gray-700">{{ $detalhe['nome'] }}</td>
                                        <td class="py-2 pl-4 text-right font-medium text-gray-900">{{ number_format($detalhe['pontos'], 2, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="py-2 text-gray-500">Nenhuma atividade foi aprovada ainda.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- NAVEGAÇÃO POR ABAS --}}
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                            <button @click="tab = 'perfil'" :class="{ 'border-blue-500 text-blue-600': tab === 'perfil', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'perfil' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Perfil do Candidato
                            </button>
                            <button @click="tab = 'analise'" :class="{ 'border-blue-500 text-blue-600': tab === 'analise', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'analise' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Análise de Documentos
                            </button>
                             <button @click="tab = 'acoes'" :class="{ 'border-blue-500 text-blue-600': tab === 'acoes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'acoes' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Ações Finais
                            </button>
                        </nav>
                    </div>

                    {{-- CONTEÚDO DAS ABAS --}}
                    
                    {{-- Aba 1: Perfil do Candidato --}}
                    <div x-show="tab === 'perfil'" x-transition>
                        @php
                        function renderDetail($label, $value) {
                            if (empty($value) && !is_numeric($value)) return;
                            echo '<div class="mb-4"><h4 class="text-sm font-medium text-gray-500">' . $label . '</h4><p class="mt-1 text-md text-gray-900">' . e($value) . '</p></div>';
                        }
                        @endphp
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados Pessoais</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6">
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
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados Acadêmicos</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6">
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
                    <div x-show="tab === 'analise'" x-transition style="display: none;">
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentos e Atividades Enviadas</h3>
                            
                            @foreach($documentosNecessarios as $tipoDocumento => $nomeDocumento)
                                @php
                                    $documentoEnviado = $documentosEnviados->get($tipoDocumento);
                                @endphp
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start gap-4 text-sm bg-gray-50">
                                    <div class="flex-grow">
                                        <div class="flex items-center">
                                            <p class="font-semibold">{{ $nomeDocumento }}</p>
                                            @if(in_array($tipoDocumento, $changed_document_types) && $documentoEnviado && $documentoEnviado->status !== 'aprovado')
                                                <span class="ml-3 px-2 py-0.5 bg-yellow-200 text-yellow-800 rounded-full text-xs font-bold">ALTERADO</span>
                                            @endif
                                        </div>
                                        @if($documentoEnviado)
                                            <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full
                                                @if($documentoEnviado->status == 'aprovado') bg-green-100 text-green-800 @endif
                                                @if($documentoEnviado->status == 'enviado') bg-blue-100 text-blue-800 @endif
                                                @if($documentoEnviado->status == 'rejeitado') bg-red-100 text-red-800 @endif
                                            ">
                                                Status: {{ $documentoEnviado->status }}
                                            </span>
                                            @if($documentoEnviado->status == 'rejeitado' && $documentoEnviado->motivo_rejeicao)
                                                <p class="text-xs text-red-700 mt-1">Motivo: {{ $documentoEnviado->motivo_rejeicao }}</p>
                                            @endif
                                        @else
                                            <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Status: Pendente</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 flex-shrink-0">
                                        @if($documentoEnviado)
                                            <a href="{{ route('candidato.documentos.show', $documentoEnviado) }}" target="_blank" class="px-3 py-1.5 bg-gray-600 text-white rounded-md text-xs hover:bg-gray-700">Visualizar</a>
                                            
                                            @if($documentoEnviado->status !== 'aprovado')
                                                <form action="{{ route('admin.documentos.updateStatus', $documentoEnviado) }}" method="POST" onsubmit="return confirm('Aprovar este documento?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="aprovado">
                                                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs hover:bg-green-700">Aprovar</button>
                                                </form>
                                            @endif

                                            @if($documentoEnviado->status !== 'rejeitado')
                                                <button @click="showDocRejectionModal = true; docRejectionAction = '{{ route('admin.documentos.updateStatus', $documentoEnviado) }}'" type="button" class="px-3 py-1.5 bg-red-600 text-white rounded-md text-xs hover:bg-red-700">Rejeitar</button>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-500">Aguardando envio do candidato.</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @foreach($candidato->atividades as $atividade)
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm bg-gray-50">
                                    <div class="flex-grow mb-3 sm:mb-0">
                                        <div class="flex items-center">
                                            @php
                                                $statusClassAtividade = 'bg-yellow-100 text-yellow-800';
                                                if ($atividade->status === 'Aprovada') $statusClassAtividade = 'bg-green-100 text-green-800';
                                                elseif ($atividade->status === 'Rejeitada') $statusClassAtividade = 'bg-red-100 text-red-800';
                                                elseif ($atividade->status === 'enviado') $statusClassAtividade = 'bg-blue-100 text-blue-800';
                                                elseif ($atividade->status === 'Em Análise') $statusClassAtividade = 'bg-purple-100 text-purple-800';
                                            @endphp
                                            <span class="font-medium capitalize px-2 py-1 rounded-full text-xs mr-3 {{ $statusClassAtividade }}">{{ $atividade->status }}</span>
                                            <p class="font-semibold">{{ $atividade->tipoDeAtividade->nome ?? 'Regra não encontrada' }}</p>
                                        </div>
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
                                            <div class="text-xs text-red-700 mt-2 p-2 bg-red-50 rounded-md">
                                                <p><strong>Motivo:</strong> {{ $atividade->motivo_rejeicao }}</p>
                                                @if($atividade->prazo_recurso_ate)
                                                    @if(\Carbon\Carbon::now()->lt($atividade->prazo_recurso_ate))
                                                        <p class="mt-1 text-blue-700">
                                                            <strong>Prazo para Recurso:</strong> {{ \Carbon\Carbon::parse($atividade->prazo_recurso_ate)->format('d/m/Y H:i') }}
                                                        </p>
                                                    @else
                                                        <p class="mt-1 text-gray-600"><strong>Prazo para Recurso Encerrado.</strong></p>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 flex-shrink-0">
                                        <a href="{{ route('candidato.atividades.show', $atividade) }}" target="_blank" class="px-3 py-1.5 bg-gray-600 text-white rounded-md text-xs hover:bg-gray-700">Visualizar</a>
                                        
                                        @if ($atividade->status !== 'Aprovada')
                                            <form action="{{ route('admin.atividades.aprovar', $atividade->id) }}" method="POST" onsubmit="return confirm('Aprovar este item?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs hover:bg-green-700">Aprovar Item</button>
                                            </form>
                                        @endif

                                        @if ($atividade->status !== 'Rejeitada')
                                            <button @click="showRejectionModal = true; rejectionAction = '{{ route('admin.atividades.rejeitar', $atividade->id) }}'" type="button" class="px-3 py-1.5 bg-red-600 text-white rounded-md text-xs hover:bg-red-700">Rejeitar Item</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Aba 3: Ações Finais --}}
                    <div x-show="tab === 'acoes'" x-transition x-cloak>
                        <div class="mt-8 pt-6 border-t p-4 bg-gray-100 rounded-lg">
                           <h3 class="text-lg font-semibold text-gray-800 mb-2">Painel de Ações do Administrador</h3>
                           
                           {{-- NOVO PAINEL DE HISTÓRICO DE RECURSOS --}}
                           <div class="mt-6">
                               <h4 class="text-lg font-bold text-gray-800 mb-4">Histórico e Análise de Recursos</h4>

                               @if($candidato->recurso_historico && count($candidato->recurso_historico) > 0)
                                   <div class="space-y-6">
                                       @foreach($candidato->recurso_historico as $index => $recurso)
                                           <div class="p-6 border rounded-lg 
                                               @if(empty($recurso['decisao_admin'])) bg-purple-50 border-purple-200 
                                               @elseif(strtolower($recurso['decisao_admin']) === 'deferido') bg-green-50 border-green-200
                                               @else bg-red-50 border-red-200 
                                               @endif">
                                               
                                               <div class="flex flex-wrap justify-between items-center pb-3 border-b 
                                                   @if(empty($recurso['decisao_admin'])) border-purple-200
                                                   @elseif(strtolower($recurso['decisao_admin']) === 'deferido') border-green-200
                                                   @else border-red-200
                                                   @endif">
                                                   <h5 class="font-bold text-md text-gray-900">
                                                       Recurso #{{ count($candidato->recurso_historico) - $index }}
                                                       <span class="ml-2 text-xs font-medium text-gray-500">
                                                           (Enviado em: {{ \Carbon\Carbon::parse($recurso['data_envio'])->format('d/m/Y H:i') }})
                                                       </span>
                                                   </h5>
                                                   @if(!empty($recurso['decisao_admin']))
                                                       <span class="px-3 py-1 text-xs font-bold rounded-full mt-2 sm:mt-0
                                                           @if(strtolower($recurso['decisao_admin']) === 'deferido') bg-green-200 text-green-800 
                                                           @else bg-red-200 text-red-800 @endif">
                                                           {{ ucfirst($recurso['decisao_admin']) }}
                                                       </span>
                                                   @else
                                                       <span class="px-3 py-1 text-xs font-bold rounded-full mt-2 sm:mt-0 bg-yellow-200 text-yellow-800">
                                                           Em Análise
                                                       </span>
                                                   @endif
                                               </div>
                                               
                                               <div class="mt-4">
                                                   <p class="text-sm font-semibold text-gray-700">Argumento do Candidato:</p>
                                                   <div class="mt-1 text-sm text-gray-800 bg-white p-3 rounded-md border whitespace-pre-wrap">{{ $recurso['argumento_candidato'] }}</div>
                                               </div>

                                               @if(!empty($recurso['decisao_admin']))
                                                   <div class="mt-4">
                                                       <p class="text-sm font-semibold text-gray-700">Justificativa do Administrador:</p>
                                                       <div class="mt-1 text-sm text-gray-800 bg-white p-3 rounded-md border whitespace-pre-wrap">{{ $recurso['justificativa_admin'] ?? 'Não foi fornecida uma justificativa.' }}</div>
                                                   </div>
                                               @else
                                                   <div class="mt-6 pt-4 border-t border-purple-200">
                                                       <p class="text-sm font-semibold text-gray-800 mb-2">Tomar Decisão:</p>
                                                       <div class="flex items-center gap-4">
                                                            <button 
                                                                @click="showResourceApprovalModal = true; resourceApprovalAction = '{{ route('admin.recursos.deferir', ['candidato' => $candidato, 'recurso_index' => $index]) }}'" 
                                                                type="button" 
                                                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-semibold">
                                                                Deferir Recurso
                                                            </button>
                                                            <button 
                                                                @click="showResourceDenialModal = true; resourceDenialAction = '{{ route('admin.recursos.indeferir', ['candidato' => $candidato, 'recurso_index' => $index]) }}'" 
                                                                type="button" 
                                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-semibold">
                                                                Indeferir Recurso
                                                            </button>
                                                       </div>
                                                   </div>
                                               @endif
                                           </div>
                                       @endforeach
                                   </div>
                               @else
                                   <div class="text-center py-6 bg-gray-50 rounded-lg border">
                                       <p class="text-sm text-gray-500">Nenhum recurso foi interposto por este candidato.</p>
                                   </div>
                               @endif
                           </div>

                           {{-- AÇÕES NORMAIS DO ADMIN (APROVAR/HOMOLOGAR/REJEITAR) --}}
                           @php
                                // Verifica se há algum recurso em análise no histórico.
                                $recursoEmAnalise = false;
                                if ($candidato->recurso_historico) {
                                    foreach ($candidato->recurso_historico as $recurso) {
                                        if (empty($recurso['decisao_admin'])) {
                                            $recursoEmAnalise = true;
                                            break;
                                        }
                                    }
                                }
                           @endphp

                           {{-- Só mostra as outras ações se não houver um recurso em análise --}}
                           @if(!$recursoEmAnalise)
                               <div class="mt-8 pt-6 border-t border-gray-300">
                                    <p class="text-sm text-gray-600 mb-4">Após analisar todas as informações, use os botões abaixo para alterar o status da inscrição.</p>
                                   
                                   @if($prazosAtivos)
                                       <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                                           <p class="font-bold">Ações Finais Bloqueadas</p>
                                           <p>O candidato possui uma ou mais atividades com prazo de recurso em andamento. Aguarde o término do prazo para prosseguir.</p>
                                       </div>
                                   @else
                                       @if ($candidato->status === 'Em Análise')
                                           <form action="{{ route('admin.candidatos.update', $candidato->id) }}" method="POST" onsubmit="return confirm('Você tem certeza que deseja alterar o status desta inscrição?');">
                                               @csrf
                                               @method('PUT')
                                               <div class="space-y-4">
                                                   <div>
                                                       <label for="admin_observacao" class="block text-sm font-medium text-gray-700">Justificativa / Observação</label>
                                                       <textarea name="admin_observacao" id="admin_observacao" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ $candidato->admin_observacao }}</textarea>
                                                   </div>
                                                   <div class="flex items-center space-x-4">
                                                       <button type="submit" name="status" value="Aprovado" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Aprovar Inscrição</button>
                                                       <button @click="showProfileRejectionModal = true" type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">Rejeitar Inscrição</button>
                                                   </div>
                                               </div>
                                           </form>
                                       @elseif ($candidato->status === 'Aprovado')
                                           <form action="{{ route('admin.candidatos.homologar', $candidato->id) }}" method="POST" class="w-full mt-4">
                                               @csrf
                                               <div class="bg-yellow-50 p-4 rounded-lg mt-4 mb-4">
                                                   <p class="font-bold text-yellow-800 mb-2">Ação: Homologar Candidato</p>
                                                   <div class="mb-3">
                                                       <label for="ato_homologacao" class="block text-sm font-medium text-gray-700">Número/Referência do Ato de Homologação <span class="text-red-500">*</span></label>
                                                       <input type="text" name="ato_homologacao" id="ato_homologacao" required 
                                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                                              value="{{ old('ato_homologacao', $candidato->ato_homologacao) }}">
                                                       @error('ato_homologacao')
                                                           <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                       @enderror
                                                   </div>
                                                   <div class="mb-3">
                                                       <label for="homologacao_observacoes" class="block text-sm font-medium text-gray-700">Observações (Opcional)</label>
                                                       <textarea name="homologacao_observacoes" id="homologacao_observacoes" rows="3" 
                                                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('homologacao_observacoes', $candidato->homologacao_observacoes) }}</textarea>
                                                       @error('homologacao_observacoes')
                                                           <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                       @enderror
                                                   </div>
                                                   <button type="submit" class="w-full px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-medium">Homologar Candidato</button>
                                               </div>
                                           </form>
                                       @elseif ($candidato->status === 'Homologado')
                                           <div class="bg-blue-50 p-4 rounded-lg mt-4 mb-4">
                                               <p class="font-bold text-blue-800 mb-2">Candidato Homologado!</p>
                                               <p class="text-sm text-blue-700">Ato de Homologação: <span class="font-medium">{{ $candidato->ato_homologacao ?? 'N/A' }}</span></p>
                                               <p class="text-sm text-blue-700">Homologado em: <span class="font-medium">{{ $candidato->homologado_em ? $candidato->homologado_em->format('d/m/Y H:i') : 'N/A' }}</span></p>
                                               @if ($candidato->homologacao_observacoes)
                                                   <p class="text-sm text-blue-700 mt-2">Observações: <span class="font-medium">{{ $candidato->homologacao_observacoes }}</span></p>
                                               @endif
                                           </div>
                                       @else
                                           <div class="bg-gray-50 p-4 rounded-lg mt-4 mb-4">
                                               <p class="font-bold text-gray-800 mb-2">Ações Atuais:</p>
                                               <p class="text-sm text-gray-700">O status do candidato não permite homologação ou aprovação direta no momento.</p>
                                           </div>
                                       @endif
                                   @endif
                               </div>
                           @endif
                        </div>
                    </div>

                    {{-- MODAIS --}}
                    {{-- Modal para rejeitar atividade --}}
                    <div x-show="showRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showRejectionModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Justificar Rejeição</h3>
                            <form :action="rejectionAction" method="POST">
                                @csrf
                                <div>
                                    <label for="motivo_rejeicao" class="block text-sm font-medium text-gray-700">Por favor, descreva o motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="motivo_rejeicao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showRejectionModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Rejeição</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal para rejeitar inscrição --}}
                    <div x-show="showProfileRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showProfileRejectionModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rejeitar Inscrição do Candidato</h3>
                            <form action="{{ route('admin.candidatos.update', $candidato->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Rejeitado">
                                <div>
                                    <label for="profile_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Por favor, descreva o motivo da rejeição da inscrição:</label>
                                    <textarea name="admin_observacao" id="profile_motivo_rejeicao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showProfileRejectionModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Rejeição da Inscrição</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal para rejeitar documento --}}
                    <div x-show="showDocRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showDocRejectionModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Justificar Rejeição do Documento</h3>
                            <form :action="docRejectionAction" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejeitado">
                                <div>
                                    <label for="doc_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Por favor, descreva o motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="doc_motivo_rejeicao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showDocRejectionModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Rejeição</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- MODAL PARA INDEFERIR RECURSO --}}
                    <div x-show="showResourceDenialModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceDenialModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Indeferir Recurso</h3>
                            <form :action="resourceDenialAction" method="POST">
                                @csrf
                                <div>
                                    <label for="indeferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa Final (será visível para o candidato)</label>
                                    <textarea name="justificativa_admin" id="indeferimento_motivo" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10" placeholder="Ex: Os argumentos apresentados não alteram a decisão inicial, pois o requisito X do edital não foi cumprido."></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showResourceDenialModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Indeferimento</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- MODAL PARA DEFERIR RECURSO --}}
                    <div x-show="showResourceApprovalModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceApprovalModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Deferir Recurso</h3>
                             <form :action="resourceApprovalAction" method="POST">
                                @csrf
                                <div>
                                    <label for="deferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa da Decisão (Opcional)</label>
                                    <textarea name="justificativa_admin" id="deferimento_motivo" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: Após reanálise da documentação, a pontuação foi ajustada."></textarea>
                                </div>
                                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                                    <strong>Atenção:</strong> Ao deferir este recurso, você está concordando com o candidato. É sua responsabilidade reanalisar as atividades e documentos e fazer os ajustes necessários manualmente.
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showResourceApprovalModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">Confirmar Deferimento</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>