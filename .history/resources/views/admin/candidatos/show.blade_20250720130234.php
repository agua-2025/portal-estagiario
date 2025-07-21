<x-app-layout>
    {{-- ✅ AJUSTE: Adiciona variáveis para o novo modal de rejeição de documentos --}}
    <div class="py-12" x-data="{ tab: 'analise', showRejectionModal: false, rejectionAction: '', showScoreDetails: false, showProfileRejectionModal: false, showDocRejectionModal: false, docRejectionAction: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- CABEÇALHO COM STATUS --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b border-gray-200 pb-6">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-800">{{ $candidato->nome_completo ?? $candidato->user->name }}</h2>
                            <p class="text-sm text-gray-500 mt-1">Inscrição recebida em: {{ $candidato->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-center gap-4">
                            @php
                                $statusClass = match($candidato->status) {
                                    'Inscrição Incompleta' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'Em Análise' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'Aprovado' => 'bg-green-100 text-green-800 border-green-300',
                                    'Homologado' => 'bg-purple-100 text-purple-800 border-purple-300',
                                    'Rejeitado' => 'bg-red-100 text-red-800 border-red-300',
                                    default => 'bg-gray-100 text-gray-800 border-gray-300',
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full border {{ $statusClass }}">
                                {{ $candidato->status }}
                            </span>
                            <a href="{{ route('admin.candidatos.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
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
                        <div class="mb-6 p-4 border-l-4 border-yellow-400 bg-yellow-50 text-yellow-800 rounded-r-lg flex items-start" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-bold">Atenção: Perfil Modificado</p>
                                <p class="text-sm">As informações do perfil do candidato foram alteradas e precisam de reanálise.</p>
                            </div>
                        </div>
                    @endif


                    {{-- PLACAR DE PONTUAÇÃO --}}
                    <div class="mb-8 p-6 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-blue-100 uppercase tracking-wider">Pontuação Total</h3>
                                <p class="mt-1 text-4xl font-bold">{{ number_format($pontuacaoTotal, 2, ',', '.') }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-300 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- NOVA NAVEGAÇÃO POR ABAS --}}
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button @click="tab = 'perfil'" :class="{ 'border-blue-500 text-blue-600': tab === 'perfil', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'perfil' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                                Perfil do Candidato
                            </button>
                            <button @click="tab = 'analise'" :class="{ 'border-blue-500 text-blue-600': tab === 'analise', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'analise' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                                Análise de Documentos
                            </button>
                             <button @click="tab = 'acoes'" :class="{ 'border-blue-500 text-blue-600': tab === 'acoes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'acoes' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
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
                            echo '<div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4"><dt class="text-sm font-medium text-gray-500">' . e($label) . '</dt><dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">' . e($value) . '</dd></div>';
                        }
                        @endphp
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Dados Pessoais</h3>
                            <dl class="divide-y divide-gray-200">
                                {{ renderDetail('Nome da Mãe', $candidato->nome_mae) }}
                                {{ renderDetail('Nome do Pai', $candidato->nome_pai) }}
                                {{ renderDetail('Data de Nascimento', optional($candidato->data_nascimento)->format('d/m/Y')) }}
                                {{ renderDetail('Sexo', $candidato->sexo) }}
                                {{ renderDetail('CPF', $candidato->cpf) }}
                                {{ renderDetail('RG', $candidato->rg) }}
                                {{ renderDetail('Órgão Expedidor', $candidato->rg_orgao_expedidor) }}
                                {{ renderDetail('Telefone', $candidato->telefone) }}
                                {{ renderDetail('Possui Deficiência?', $candidato->possui_deficiencia ? 'Sim' : 'Não') }}
                            </dl>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Dados Acadêmicos</h3>
                            <dl class="divide-y divide-gray-200">
                               {{ renderDetail('Instituição de Ensino', $candidato->instituicao->nome ?? 'N/A') }}
                                {{ renderDetail('Curso', $candidato->curso->nome ?? 'N/A') }}
                                {{ renderDetail('Início do Curso', optional($candidato->curso_data_inicio)->format('d/m/Y')) }}
                                {{ renderDetail('Previsão de Conclusão', optional($candidato->curso_previsao_conclusao)->format('d/m/Y')) }}
                                {{ renderDetail('Média de Aproveitamento', $candidato->media_aproveitamento) }}
                                {{ renderDetail('Semestres Concluídos', $candidato->semestres_completos) }}
                            </dl>
                        </div>
                    </div>

                    {{-- Aba 2: Análise de Documentos --}}
                    <div x-show="tab === 'analise'" x-transition style="display: none;">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentos Obrigatórios</h3>
                                <div class="space-y-4">
                                    @foreach($documentosNecessarios as $tipoDocumento => $nomeDocumento)
                                        @php
                                            $documentoEnviado = $documentosEnviados->get($tipoDocumento);
                                        @endphp
                                        <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start gap-4 text-sm bg-gray-50/70">
                                            <div class="flex-grow">
                                                <div class="flex items-center">
                                                    <p class="font-semibold text-gray-700">{{ $nomeDocumento }}</p>
                                                    @if(in_array($tipoDocumento, $changed_document_types) && $documentoEnviado && $documentoEnviado->status !== 'aprovado')
                                                        <span class="ml-3 px-2 py-0.5 bg-yellow-200 text-yellow-800 rounded-full text-xs font-bold">ALTERADO</span>
                                                    @endif
                                                </div>
                                                @if($documentoEnviado)
                                                    <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full border
                                                        @if($documentoEnviado->status == 'aprovado') bg-green-100 text-green-800 border-green-200 @endif
                                                        @if($documentoEnviado->status == 'enviado') bg-blue-100 text-blue-800 border-blue-200 @endif
                                                        @if($documentoEnviado->status == 'rejeitado') bg-red-100 text-red-800 border-red-200 @endif
                                                    ">
                                                        {{ $documentoEnviado->status }}
                                                    </span>
                                                    @if($documentoEnviado->status == 'rejeitado' && $documentoEnviado->motivo_rejeicao)
                                                        <p class="text-xs text-red-700 mt-2 p-2 bg-red-50 rounded border border-red-100"><strong>Motivo:</strong> {{ $documentoEnviado->motivo_rejeicao }}</p>
                                                    @endif
                                                @else
                                                    <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full bg-gray-100 text-gray-800 border border-gray-200">Pendente</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2 flex-shrink-0">
                                                @if($documentoEnviado)
                                                    <a href="{{ route('candidato.documentos.show', $documentoEnviado) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-xs hover:bg-gray-50 transition">Visualizar</a>
                                                    @if($documentoEnviado->status !== 'aprovado')
                                                        <form action="{{ route('admin.documentos.updateStatus', $documentoEnviado) }}" method="POST" onsubmit="return confirm('Aprovar este documento?');">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="aprovado">
                                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent text-white rounded-md text-xs hover:bg-green-700 transition">Aprovar</button>
                                                        </form>
                                                    @endif
                                                    @if($documentoEnviado->status !== 'rejeitado')
                                                        <button @click="showDocRejectionModal = true; docRejectionAction = '{{ route('admin.documentos.updateStatus', $documentoEnviado) }}'" type="button" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent text-white rounded-md text-xs hover:bg-red-700 transition">Rejeitar</button>
                                                    @endif
                                                @else
                                                    <span class="text-xs text-gray-500 italic">Aguardando envio.</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Atividades Pontuáveis</h3>
                                <div class="space-y-4">
                                    @forelse($candidato->user->candidatoAtividades as $atividade)
                                        <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start gap-4 text-sm bg-gray-50/70">
                                            <div class="flex-grow">
                                                <div class="flex items-center mb-1">
                                                    @php
                                                        $statusClassAtividade = match($atividade->status) {
                                                            'Aprovada' => 'bg-green-100 text-green-800 border-green-200',
                                                            'Rejeitada' => 'bg-red-100 text-red-800 border-red-200',
                                                            'enviado' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                            'Em Análise' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                                                        };
                                                    @endphp
                                                    <span class="font-medium capitalize px-2 py-0.5 rounded-full text-xs mr-3 border {{ $statusClassAtividade }}">{{ $atividade->status }}</span>
                                                    <p class="font-semibold text-gray-800">{{ $atividade->tipoDeAtividade->nome ?? 'Regra não encontrada' }}</p>
                                                </div>
                                                <p class="text-xs text-gray-600 pl-4 border-l-2 border-gray-200 ml-2">{{ $atividade->descricao_customizada }}</p>
                                                
                                                @if($atividade->status === 'Rejeitada' && $atividade->motivo_rejeicao)
                                                    <div class="text-xs text-red-700 mt-2 p-2 bg-red-50 rounded border border-red-100">
                                                        <p><strong>Motivo:</strong> {{ $atividade->motivo_rejeicao }}</p>
                                                        @if($atividade->prazo_recurso_ate)
                                                            @if(\Carbon\Carbon::now()->lt($atividade->prazo_recurso_ate))
                                                                <p class="mt-1 text-blue-700"><strong>Prazo para Recurso:</strong> {{ \Carbon\Carbon::parse($atividade->prazo_recurso_ate)->format('d/m/Y H:i') }}</p>
                                                            @else
                                                                <p class="mt-1 text-gray-600"><strong>Prazo para Recurso Encerrado.</strong></p>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2 flex-shrink-0">
                                                <a href="{{ route('candidato.atividades.show', $atividade) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-md text-xs hover:bg-gray-50 transition">Visualizar</a>
                                                @if ($atividade->status !== 'Aprovada')
                                                    <form action="{{ route('admin.atividades.aprovar', $atividade->id) }}" method="POST" onsubmit="return confirm('Aprovar este item?');">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent text-white rounded-md text-xs hover:bg-green-700 transition">Aprovar Item</button>
                                                    </form>
                                                @endif
                                                @if ($atividade->status !== 'Rejeitada')
                                                    <button @click="showRejectionModal = true; rejectionAction = '{{ route('admin.atividades.rejeitar', $atividade->id) }}'" type="button" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent text-white rounded-md text-xs hover:bg-red-700 transition">Rejeitar Item</button>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-gray-500 py-4">Nenhuma atividade pontuável enviada.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aba 3: Ações Finais --}}
                    <div x-show="tab === 'acoes'" x-transition x-cloak>
                       {{-- ... (código da aba de ações inalterado) ... --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAIS --}}
        {{-- ... (código dos modais inalterado, mas poderiam ser estilizados também) ... --}}
        <div x-show="showRejectionModal" ... > ... </div>
        <div x-show="showProfileRejectionModal" ... > ... </div>
        <div x-show="showDocRejectionModal" ... > ... </div>

    </div>
</x-app-layout>
