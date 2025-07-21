<x-app-layout>
    {{-- ✅ AJUSTE: Adiciona variáveis para o novo modal de rejeição de documentos --}}
    <div class="py-12" x-data="{ tab: 'analise', showRejectionModal: false, rejectionAction: '', showScoreDetails: false, showProfileRejectionModal: false, showDocRejectionModal: false, docRejectionAction: '' }">
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

                        $prazosAtivos = $candidato->user->candidatoAtividades()
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
                        {{-- ... (código do placar inalterado) ... --}}
                    </div>

                    {{-- NOVA NAVEGAÇÃO POR ABAS --}}
                    <div class="border-b border-gray-200 mb-6">
                        {{-- ... (código das abas inalterado) ... --}}
                    </div>

                    {{-- CONTEÚDO DAS ABAS --}}
                    
                    {{-- Aba 1: Perfil do Candidato --}}
                    <div x-show="tab === 'perfil'" x-transition>
                        {{-- ... (conteúdo da aba de perfil inalterado) ... --}}
                    </div>

                    {{-- Aba 2: Análise de Documentos --}}
                    <div x-show="tab === 'analise'" x-transition style="display: none;">
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentos e Atividades Enviadas</h3>
                            
                            {{-- ✅ INÍCIO DA SEÇÃO ATUALIZADA --}}
                            @foreach($documentosNecessarios as $tipoDocumento => $nomeDocumento)
                                @php
                                    $documentoEnviado = $documentosEnviados->get($tipoDocumento);
                                @endphp
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start gap-4 text-sm bg-gray-50">
                                    {{-- Informações do Documento --}}
                                    <div class="flex-grow">
                                        <div class="flex items-center">
                                            <p class="font-semibold">{{ $nomeDocumento }}</p>
                                            @if(in_array($tipoDocumento, $changed_document_types))
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
                                    
                                    {{-- Ações do Admin para o Documento --}}
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
                            {{-- ✅ FIM DA SEÇÃO ATUALIZADA --}}

                            @foreach($candidato->user->candidatoAtividades as $atividade)
                                {{-- ... (código das atividades inalterado) ... --}}
                            @endforeach
                        </div>
                    </div>

                    {{-- Aba 3: Ações Finais --}}
                    <div x-show="tab === 'acoes'" x-transition x-cloak>
                       {{-- ... (conteúdo da aba de ações inalterado) ... --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DE REJEIÇÃO DE ATIVIDADES (inalterado) --}}
        <div x-show="showRejectionModal" x-transition ... >
            {{-- ... --}}
        </div>

        {{-- MODAL DE REJEIÇÃO DE INSCRIÇÃO (inalterado) --}}
        <div x-show="showProfileRejectionModal" x-transition ... >
            {{-- ... --}}
        </div>

        {{-- ✅ NOVO MODAL: Para rejeição de documentos individuais --}}
        <div x-show="showDocRejectionModal" x-transition class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
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

    </div>
</x-app-layout>