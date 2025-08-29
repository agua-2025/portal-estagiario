<x-app-layout>
    <div class="py-8" x-data="{ tab: 'analise', showRejectionModal: false, rejectionAction: '', showScoreDetails: false, showProfileRejectionModal: false, showDocRejectionModal: false, docRejectionAction: '', showResourceDenialModal: false, resourceDenialAction: '', showResourceApprovalModal: false, resourceApprovalAction: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- CABEÇALHO COM STATUS --}}
                    <div class="flex flex-col lg:flex-row justify-between items-start mb-4 border-b pb-3">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ $candidato->nome_completo_formatado ?? $candidato->user->name }}</h2>
                            <p class="text-sm text-gray-500">Inscrição: {{ $candidato->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-3 lg:mt-0 flex items-center gap-3">
                            @php
                                $statusClass = 'bg-gray-100 text-gray-800';
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
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                            <a href="{{ route('admin.candidatos.index') }}" class="px-3 py-1 bg-gray-200 border border-transparent rounded text-xs text-gray-700 hover:bg-gray-300">Voltar</a>
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
                        $prazosAtivos = $candidato->atividades()->where('status', 'Rejeitada')->where('prazo_recurso_ate', '>', now())->exists();
                    @endphp

                    {{-- Alerta GLOBAL se o perfil foi alterado --}}
                    @if($profile_was_updated)
                        <div class="mb-4 p-3 border-l-4 border-yellow-500 bg-yellow-50 text-yellow-800 rounded text-sm" role="alert">
                            <p><span class="font-semibold">Atenção:</span> Informações do perfil alteradas - requer reanálise.</p>
                        </div>
                    @endif

                    {{-- PLACAR DE PONTUAÇÃO --}}
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-blue-800">Pontuação Total (Itens Aprovados)</h3>
                                <p class="mt-1 text-2xl font-bold text-blue-900">{{ number_format($pontuacaoTotal, 2, ',', '.') }} pontos</p>
                            </div>
                            <button @click="showScoreDetails = !showScoreDetails" class="text-xs text-blue-600 hover:underline px-2 py-1">
                                <span x-show="!showScoreDetails">Ver Detalhes</span>
                                <span x-show="showScoreDetails">Ocultar</span>
                            </button>
                        </div>
                        <div x-show="showScoreDetails" x-transition class="mt-3 pt-3 border-t border-blue-200">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase mb-2">Extrato de Pontos</h4>
                            <table class="min-w-full text-xs">
                                <tbody>
                                    @forelse($detalhesPontuacao as $detalhe)
                                    <tr class="border-b border-blue-100 last:border-b-0">
                                        <td class="py-1 pr-4 text-gray-700">{{ $detalhe['nome'] }}</td>
                                        <td class="py-1 pl-4 text-right font-medium text-gray-900">{{ number_format($detalhe['pontos'], 2, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td class="py-1 text-gray-500">Nenhuma atividade aprovada.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- NAVEGAÇÃO POR ABAS (estilo pill + badges) --}}
                    @php
                        // Conta documentos pendentes (faltando, enviados ou rejeitados)
                        $docsPendentes = 0;
                        foreach ($documentosNecessarios as $tipo => $rotulo) {
                            $doc = $documentosEnviados->get($tipo);
                            if (!$doc || $doc->status !== 'aprovado') {
                                $docsPendentes++;
                            }
                        }
                        // Conta atividades não aprovadas (para eventual badge futuro)
                        $atividadesPendentes = $candidato->atividades()->whereIn('status', ['enviado','Em Análise','Rejeitada'])->count();
                    @endphp

                    <div class="mb-4">
                    <div class="inline-flex w-full sm:w-auto bg-gray-100/80 rounded-xl p-1 shadow-inner backdrop-blur">
                        {{-- PERFIL --}}
                        <button type="button"
                        @click="tab = 'perfil'"
                        :class="tab === 'perfil'
                            ? 'bg-white shadow text-blue-700'
                            : 'text-gray-600 hover:text-gray-800'"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all">
                        {{-- ícone usuário --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A9 9 0 1118.88 6.196 9 9 0 015.12 17.804zM15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                        </svg>
                        <span>Perfil</span>
                        </button>

                        {{-- DOCUMENTOS --}}
                        <button type="button"
                        @click="tab = 'analise'"
                        :class="tab === 'analise'
                            ? 'bg-white shadow text-blue-700'
                            : 'text-gray-600 hover:text-gray-800'"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all">
                        {{-- ícone pasta --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                        </svg>
                        <span>Documentos</span>

                        {{-- badge de pendências --}}
                        @if($docsPendentes > 0)
                            <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold rounded-full bg-red-600 text-white">
                            {{ $docsPendentes }}
                            </span>
                        @endif
                        </button>

                        {{-- AÇÕES FINAIS --}}
                        <button type="button"
                        @click="tab = 'acoes'"
                        :class="tab === 'acoes'
                            ? 'bg-white shadow text-blue-700'
                            : 'text-gray-600 hover:text-gray-800'"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all">
                        {{-- ícone martelo/gavel --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.7 5.3l4 4-9.4 9.4a2 2 0 01-1.414.586H6a2 2 0 01-2-2v-1.886a2 2 0 01.586-1.414L14.7 5.3zM13 7l4 4" />
                        </svg>
                        <span>Ações Finais</span>
                        </button>
                    </div>
                    </div>

                    {{-- Aba 1: Perfil do Candidato --}}
                    <div x-show="tab === 'perfil'" x-transition>
                        @php
                        function renderDetail($label, $value) {
                            if (empty($value) && !is_numeric($value)) return;
                            echo '<div class="mb-3"><h4 class="text-xs font-medium text-gray-500 uppercase">' . $label . '</h4><p class="mt-1 text-sm text-gray-900">' . e($value) . '</p></div>';
                        }
                        @endphp
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Dados Pessoais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                                {{ renderDetail('Nome da Mãe', \App\Support\NameCase::person($candidato->nome_mae ?? '')) }}
                                {{ renderDetail('Nome do Pai', \App\Support\NameCase::person($candidato->nome_pai ?? '')) }}
                                {{ renderDetail('Data de Nascimento', optional($candidato->data_nascimento)->format('d/m/Y')) }}
                                {{ renderDetail('Sexo', $candidato->sexo) }}
                                {{ renderDetail('CPF', $candidato->cpf) }}
                                {{ renderDetail('RG', $candidato->rg) }}
                                {{ renderDetail('Órgão Expedidor', $candidato->rg_orgao_expedidor) }}
                                {{ renderDetail('Telefone', $candidato->telefone) }}
                                {{ renderDetail('Possui Deficiência?', $candidato->possui_deficiencia ? 'Sim' : 'Não') }}
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Dados Acadêmicos</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
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
                        
                        {{-- SEÇÃO DE DOCUMENTOS --}}
                        <div class="mb-6">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Documentos Obrigatórios</h3>
                            <div class="space-y-2">
                                @foreach($documentosNecessarios as $tipoDocumento => $nomeDocumento)
                                    @php $documentoEnviado = $documentosEnviados->get($tipoDocumento); @endphp
                                    <div class="p-3 border rounded bg-gray-50 text-sm">
                                        <div class="flex justify-between items-center">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="font-medium truncate">{{ $nomeDocumento }}</p>
                                                    @if(in_array($tipoDocumento, $changed_document_types) && $documentoEnviado && $documentoEnviado->status !== 'aprovado')
                                                        <span class="px-2 py-0.5 bg-yellow-200 text-yellow-800 rounded-full text-xs font-medium flex-shrink-0">ALTERADO</span>
                                                    @endif
                                                </div>
                                                @if($documentoEnviado)
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                                            @if($documentoEnviado->status == 'aprovado') bg-green-100 text-green-800 @endif
                                                            @if($documentoEnviado->status == 'enviado') bg-blue-100 text-blue-800 @endif
                                                            @if($documentoEnviado->status == 'rejeitado') bg-red-100 text-red-800 @endif
                                                        ">{{ ucfirst($documentoEnviado->status) }}</span>
                                                    </div>
                                                    @if($documentoEnviado->status == 'rejeitado' && $documentoEnviado->motivo_rejeicao)
                                                        <p class="text-xs text-red-700 mt-1">{{ $documentoEnviado->motivo_rejeicao }}</p>
                                                    @endif
                                                @else
                                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center gap-1 ml-3 flex-shrink-0">
    @if($documentoEnviado)
        {{-- O botão 'Ver' fica sempre visível, como você pediu --}}
        <a href="{{ route('candidato.documentos.show', $documentoEnviado) }}" target="_blank" class="px-3 py-1 bg-gray-600 text-white rounded text-xs hover:bg-gray-700">Ver</a>

        {{-- ✅ Regra: SÓ mostra os botões de ação se o candidato NÃO estiver convocado --}}
        @if ($candidato->status !== 'Convocado')

            @if($documentoEnviado->status === 'rejeitado')
                <button type="button" disabled onclick="alert('Candidato deve reenviar documento corrigido.')" class="py-1 bg-gray-400 text-white rounded text-xs cursor-not-allowed w-[70px] flex justify-center items-center">Bloqueado</button>
            @elseif($documentoEnviado->status !== 'aprovado')
                <form action="{{ route('admin.documentos.updateStatus', $documentoEnviado) }}" method="POST" onsubmit="return confirm('Aprovar documento?');" class="inline">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="aprovado">
                    <button type="submit" class="py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 w-[70px] flex justify-center items-center">Aprovar</button>
                </form>
            @endif
            
            @if($documentoEnviado->status !== 'rejeitado')
                <button @click="if(confirm('Tem certeza que deseja rejeitar? Esta ação só poderá ser revista após o reenvio de um novo documento pelo candidato.')) { showDocRejectionModal = true; docRejectionAction = '{{ route('admin.documentos.updateStatus', $documentoEnviado) }}' }" type="button" class="py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 w-[70px] flex justify-center items-center">Rejeitar</button>
            @endif

        @else
            {{-- Se o candidato já foi convocado, mostra o aviso --}}
            <span class="px-3 py-1 bg-gray-700 text-white rounded text-xs w-[70px] cursor-not-allowed flex justify-center items-center" title="Ações bloqueadas pois o candidato já foi convocado.">Convocado</span>
        @endif
    @else
        <span class="text-xs text-gray-500">Aguardando envio</span>
    @endif
</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- SEÇÃO DE ATIVIDADES --}}
<div class="mb-6">
    <h3 class="text-base font-semibold text-gray-800 mb-3">Atividades Complementares</h3>
    <div class="space-y-2">
        @foreach($candidato->atividades as $atividade)
            <div class="p-3 border rounded bg-gray-50 text-sm">
                <div class="flex justify-between items-start">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @php
                                $statusClassAtividade = 'bg-yellow-100 text-yellow-800';
                                if ($atividade->status === 'Aprovada') $statusClassAtividade = 'bg-green-100 text-green-800';
                                elseif ($atividade->status === 'Rejeitada') $statusClassAtividade = 'bg-red-100 text-red-800';
                                elseif ($atividade->status === 'enviado') $statusClassAtividade = 'bg-blue-100 text-blue-800';
                                elseif ($atividade->status === 'Em Análise') $statusClassAtividade = 'bg-purple-100 text-purple-800';
                            @endphp
                            <span class="font-medium px-2 py-0.5 rounded-full text-xs {{ $statusClassAtividade }}">{{ $atividade->status }}</span>
                            <p class="font-medium truncate">{{ $atividade->tipoDeAtividade->nome ?? 'Regra não encontrada' }}</p>
                        </div>
                        <p class="text-xs text-gray-600 mb-2">{{ $atividade->descricao_customizada }}</p>
                        
                        <div class="text-xs text-gray-700 pl-2 border-l-2 border-gray-200">
                            @if (str_contains(strtolower($atividade->tipoDeAtividade->nome), 'aproveitamento acadêmico'))
                                <p><strong>Média:</strong> {{ $candidato->media_aproveitamento ?? 'N/A' }}</p>
                            @elseif($atividade->tipoDeAtividade->unidade_medida === 'horas')
                                <p><strong>Horas:</strong> {{ $atividade->carga_horaria ?? 'N/A' }}</p>
                            @elseif($atividade->tipoDeAtividade->unidade_medida === 'meses')
                                <p><strong>Período:</strong> {{ optional($atividade->data_inicio)->format('d/m/Y') ?? 'N/A' }} a {{ optional($atividade->data_fim)->format('d/m/Y') ?? 'N/A' }}</p>
                            @elseif(str_contains(strtolower($atividade->tipoDeAtividade->nome), 'semestres cursados') || $atividade->tipoDeAtividade->unidade_medida === 'semestre')
                                <p><strong>Semestres:</strong> {{ $atividade->semestres_declarados ?? 'N/A' }}</p>
                            @endif
                        </div>

                        @if($atividade->status === 'Rejeitada' && $atividade->motivo_rejeicao)
                            <div class="text-xs text-red-700 mt-2 p-2 bg-red-50 rounded">
                                <p class="break-words"><strong>Motivo:</strong> {{ $atividade->motivo_rejeicao }}</p>
                                @if($atividade->prazo_recurso_ate)
                                    @if(\Carbon\Carbon::now()->lt($atividade->prazo_recurso_ate))
                                        <p class="mt-1 text-blue-700"><strong>Prazo para Recurso:</strong> até {{ \Carbon\Carbon::parse($atividade->prazo_recurso_ate)->format('d/m/Y') }} às 17h00 (2 dias úteis)</p>
                                    @else
                                        <p class="mt-1 text-gray-600"><strong>Prazo Encerrado</strong></p>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- ✅ A ALTERAÇÃO ESTÁ AQUI DENTRO --}}
                    <div class="flex items-center gap-1 ml-3 flex-shrink-0">
                        <a href="{{ route('candidato.atividades.show', $atividade) }}" target="_blank" class="px-3 py-1 bg-gray-600 text-white rounded text-xs hover:bg-gray-700">Ver</a>

                        {{-- Regra Principal: SÓ mostra botões de ação se o candidato NÃO estiver convocado --}}
                        @if ($candidato->status !== 'Convocado')
                            @php
                                $prazoExpirado = false;
                                if ($atividade->status === 'Rejeitada' && $atividade->prazo_recurso_ate && \Carbon\Carbon::now()->gt($atividade->prazo_recurso_ate)) {
                                    $prazoExpirado = true;
                                }
                            @endphp
                            
                            {{-- Regra Secundária: Verifica se o prazo do recurso da atividade expirou --}}
                            @if (!$prazoExpirado)
                                @if ($atividade->status !== 'Aprovada')
                                    <form action="{{ route('admin.atividades.aprovar', $atividade->id) }}" method="POST" onsubmit="return confirm('Aprovar item?');" class="inline">
                                        @csrf
                                        <button type="submit" class="py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 w-[70px] flex justify-center items-center">Aprovar</button>
                                    </form>
                                @endif
                                @if ($atividade->status !== 'Rejeitada')
                                    <button @click="showRejectionModal = true; rejectionAction = '{{ route('admin.atividades.rejeitar', $atividade->id) }}'" type="button" class="py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 w-[70px] flex justify-center items-center">Rejeitar</button>
                                @endif
                            @else
                                <span class="px-3 py-1 bg-gray-400 text-white rounded text-xs w-[70px] cursor-not-allowed flex justify-center items-center" title="O prazo para o candidato recorrer já encerrou.">Bloqueado</span>
                            @endif
                        @else
                            {{-- Se o candidato já foi convocado, mostra o aviso final --}}
                            <span class="px-3 py-1 bg-gray-700 text-white rounded text-xs w-[70px] cursor-not-allowed flex justify-center items-center" title="Ações bloqueadas pois o candidato já foi convocado.">Convocado</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>

                    {{-- Aba 3: Ações Finais --}}
                    <div x-show="tab === 'acoes'" x-transition x-cloak>
                        <div class="mt-4 p-4 bg-gray-50 rounded">
                           <h3 class="text-base font-semibold text-gray-800 mb-3">Painel de Ações</h3>
                           
                           <div class="mt-4">
                               <h4 class="text-sm font-bold text-gray-800 mb-3">Histórico de Recursos</h4>
                               @if($candidato->recurso_historico && count($candidato->recurso_historico) > 0)
                                   <div class="space-y-4">
                                       @foreach($candidato->recurso_historico as $index => $recurso)
                                           <div class="p-4 border rounded 
                                               @if(empty($recurso['decisao_admin'])) bg-purple-50 border-purple-200 
                                               @elseif(strtolower($recurso['decisao_admin']) === 'deferido') bg-green-50 border-green-200
                                               @else bg-red-50 border-red-200 @endif">
                                               
                                               <div class="flex justify-between items-center pb-2 border-b 
                                                   @if(empty($recurso['decisao_admin'])) border-purple-200
                                                   @elseif(strtolower($recurso['decisao_admin']) === 'deferido') border-green-200
                                                   @else border-red-200 @endif">
                                                   <h5 class="font-medium text-sm text-gray-900">
                                                       Recurso #{{ count($candidato->recurso_historico) - $index }}
                                                       <span class="ml-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($recurso['data_envio'])->format('d/m/Y H:i') }}</span>
                                                   </h5>
                                                   @if(!empty($recurso['decisao_admin']))
                                                       <span class="px-2 py-1 text-xs font-medium rounded-full
                                                           @if(strtolower($recurso['decisao_admin']) === 'deferido') bg-green-200 text-green-800 
                                                           @else bg-red-200 text-red-800 @endif">{{ ucfirst($recurso['decisao_admin']) }}</span>
                                                   @else
                                                       <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-200 text-yellow-800">Em Análise</span>
                                                   @endif
                                               </div>
                                               
                                               <div class="mt-2">
                                                   <p class="text-xs font-medium text-gray-700">Argumento:</p>
                                                   <div class="mt-1 text-xs text-gray-800 bg-white p-2 rounded border whitespace-pre-wrap break-all">{{ $recurso['argumento_candidato'] }}</div>
                                               </div>

                                               @if(!empty($recurso['decisao_admin']))
                                                   <div class="mt-2">
                                                       <p class="text-xs font-medium text-gray-700">Justificativa:</p>
                                                       <div class="mt-1 text-xs text-gray-800 bg-white p-2 rounded border whitespace-pre-wrap break-all">{{ $recurso['justificativa_admin'] ?? 'Não fornecida.' }}</div>


                                                   </div>
                                               @else
                                                   <div class="mt-3 pt-2 border-t border-purple-200">
                                                       <p class="text-xs font-medium text-gray-800 mb-2">Decisão:</p>
                                                       <div class="flex gap-2">
                                                            <button @click="showResourceApprovalModal = true; resourceApprovalAction = '{{ route('admin.recursos.deferir', ['candidato' => $candidato, 'recurso_index' => $index]) }}'" type="button" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Deferir</button>
                                                            <button @click="showResourceDenialModal = true; resourceDenialAction = '{{ route('admin.recursos.indeferir', ['candidato' => $candidato, 'recurso_index' => $index]) }}'" type="button" class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Indeferir</button>
                                                       </div>
                                                   </div>
                                               @endif
                                           </div>
                                       @endforeach
                                   </div>
                               @else
                                   <div class="text-center py-4 bg-gray-50 rounded border">
                                       <p class="text-xs text-gray-500">Nenhum recurso interposto.</p>
                                   </div>
                               @endif
                           </div>

                           @php
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

                           @if(!$recursoEmAnalise)
                               <div class="mt-6 pt-4 border-t border-gray-300">
                                    <p class="text-xs text-gray-600 mb-3">Ações finais da inscrição:</p>
                                   
                                   @if($prazosAtivos)
                                       <div class="p-3 bg-yellow-100 text-yellow-800 rounded">
                                           <p class="font-medium text-sm">Ações Bloqueadas</p>
                                           <p class="text-xs">Aguarde término dos prazos de recurso.</p>
                                       </div>
                                   @else
                                       @if ($candidato->status === 'Em Análise')
                                           <form action="{{ route('admin.candidatos.update', $candidato->id) }}" method="POST" onsubmit="return confirm('Alterar status da inscrição?');">
                                               @csrf @method('PUT')
                                               <div class="space-y-3">
                                                   <div>
                                                       <label for="admin_observacao" class="block text-xs font-medium text-gray-700">Justificativa</label>
                                                       <textarea name="admin_observacao" id="admin_observacao" rows="2" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-xs">{{ $candidato->admin_observacao }}</textarea>
                                                   </div>
                                                   <div class="flex gap-2">
                                                       <button type="submit" name="status" value="Aprovado" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Aprovar</button>
                                                       <button @click="showProfileRejectionModal = true" type="button" class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Rejeitar</button>
                                                   </div>
                                               </div>
                                           </form>
                                    @elseif ($candidato->status === 'Aprovado')
                                        <form action="{{ route('admin.candidatos.homologar', $candidato->id) }}" method="POST" class="w-full">
                                            @csrf
                                            <div class="bg-yellow-50 p-3 rounded">
                                                <p class="font-medium text-yellow-800 mb-2 text-sm">Homologar Candidato</p>
                                                
                                                {{-- ✅ REMOVIDO: Campo "Nº do Ato" --}}
                                                
                                                <div class="mb-2">
                                                    <label for="homologacao_observacoes" class="block text-xs font-medium text-gray-700">Observações</label>
                                                    <textarea name="homologacao_observacoes" id="homologacao_observacoes" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-xs" placeholder="Observações sobre a homologação (opcional)">{{ old('homologacao_observacoes', $candidato->homologacao_observacoes) }}</textarea>
                                                    @error('homologacao_observacoes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                                </div>
                                                
                                                <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded text-xs hover:bg-purple-700">Homologar</button>
                                            </div>
                                        </form>
                                       {{-- EXIBIÇÃO APÓS HOMOLOGAÇÃO TAMBÉM CORRIGIDA --}}
                                    @elseif ($candidato->status === 'Homologado')
                                        <div class="bg-blue-50 p-3 rounded">
                                            <p class="font-medium text-blue-800 mb-1 text-sm">Candidato Homologado</p>
                                            
                                            {{-- ✅ REMOVIDO: Exibição do "Ato" --}}
                                            
                                            <p class="text-xs text-blue-700">Data: {{ $candidato->homologado_em ? $candidato->homologado_em->format('d/m/Y H:i') : 'N/A' }}</p>
                                            @if ($candidato->homologacao_observacoes)
                                                <p class="text-xs text-blue-700 mt-1">Obs: {{ $candidato->homologacao_observacoes }}</p>
                                            @endif
                                        </div>
                                       @else
                                           <div class="bg-gray-50 p-3 rounded">
                                               <p class="font-medium text-gray-800 mb-1 text-sm">Status Atual</p>
                                               <p class="text-xs text-gray-700">Sem ações disponíveis no momento.</p>
                                           </div>
                                       @endif
                                   @endif
                               </div>
                           @endif
                        </div>
                    </div>

                    {{-- MODAIS --}}
                    <div x-show="showRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showRejectionModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Justificar Rejeição</h3>
                            <form :action="rejectionAction" method="POST">
                                @csrf
                                <div>
                                    <label for="motivo_rejeicao" class="block text-sm font-medium text-gray-700">Motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="motivo_rejeicao" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showRejectionModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="showProfileRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showProfileRejectionModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Rejeitar Inscrição</h3>
                            <form action="{{ route('admin.candidatos.update', $candidato->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="Rejeitado">
                                <div>
                                    <label for="profile_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Motivo da rejeição:</label>
                                    <textarea name="admin_observacao" id="profile_motivo_rejeicao" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showProfileRejectionModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="showDocRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showDocRejectionModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Rejeitar Documento</h3>
                            <form :action="docRejectionAction" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="rejeitado">
                                <div>
                                    <label for="doc_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="doc_motivo_rejeicao" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showDocRejectionModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="showResourceDenialModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceDenialModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Indeferir Recurso</h3>
                            <form :action="resourceDenialAction" method="POST">
                                @csrf
                                <div>
                                    <label for="indeferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa (visível ao candidato):</label>
                                    <textarea name="justificativa_admin" id="indeferimento_motivo" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10" placeholder="Ex: Argumentos não alteram decisão inicial..."></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showResourceDenialModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Indeferir</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div x-show="showResourceApprovalModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceApprovalModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Deferir Recurso</h3>
                             <form :action="resourceApprovalAction" method="POST">
                                @csrf
                                <div>
                                    <label for="deferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa (Opcional):</label>
                                    <textarea name="justificativa_admin" id="deferimento_motivo" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" placeholder="Ex: Após reanálise, pontuação foi ajustada."></textarea>
                                </div>
                                <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
                                    <strong>Atenção:</strong> Você deve reanalisar manualmente as atividades e documentos.
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showResourceApprovalModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Deferir</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>