<x-app-layout>
    {{-- O x-data agora controla o modal E a aba ativa --}}
    <div class="py-12" x-data="{ tab: 'analise', showRejectionModal: false, rejectionAction: '', showScoreDetails: false, showProfileRejectionModal: false }"> {{-- ✅ showProfileRejectionModal mantido para o modal --}}
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- CABEÇALHO --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $candidato->nome_completo ?? $candidato->user->name }}</h2>
                            <p class="text-sm text-gray-500">Inscrição recebida em: {{ $candidato->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-center gap-4">
                               @php
                                   $statusClass = 'bg-yellow-100 text-yellow-800';
                                   $statusText = 'Aguardando Análise';
                                   if ($candidato->status === 'Aprovado') {
                                       $statusClass = 'bg-green-100 text-green-800';
                                       $statusText = 'Aprovado';
                                   } elseif ($candidato->status === 'Rejeitado') {
                                       $statusClass = 'bg-red-100 text-red-800';
                                       $statusText = 'Rejeitado';
                                   } elseif ($candidato->status === 'Em Análise') { // Adicionado para clareza
                                       $statusClass = 'bg-purple-100 text-purple-800';
                                       $statusText = 'Em Análise';
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

                    {{-- NOVA NAVEGAÇÃO POR ABAS --}}
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
                               {{ renderDetail('Instituição de Ensino', $candidato->curso->instituicao->nome ?? 'N/A') }}
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
                        {{-- ✅ REMOVIDO: BOTÕES DE APROVAÇÃO/REJEIÇÃO DO PERFIL GERAL (Conforme sua solicitação) --}}
                        {{-- Eles pertencem à aba "Ações Finais" --}}
                        
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentos e Atividades Enviadas</h3>
                            @foreach($candidato->user->documentos as $documento)
                                <div class="p-3 border rounded-lg flex justify-between items-center text-sm bg-gray-50">
                                    <span>{{ str_replace('_', ' ', $documento->tipo_documento) }}</span>
                                    <a href="{{ route('candidato.documentos.show', $documento) }}" target="_blank" class="px-4 py-1.5 bg-gray-600 text-white rounded-lg text-xs hover:bg-gray-700">Visualizar</a>
                                </div>
                            @endforeach
                            @foreach($candidato->user->candidatoAtividades as $atividade)
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm bg-gray-50">
                                    <div class="flex-grow mb-3 sm:mb-0">
                                        <div class="flex items-center">
                                            @php
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                if ($atividade->status === 'Aprovada') $statusClass = 'bg-green-100 text-green-800';
                                                elseif ($atividade->status === 'Rejeitada') $statusClass = 'bg-red-100 text-red-800';
                                                elseif ($atividade->status === 'enviado') $statusClass = 'bg-blue-100 text-blue-800';
                                                elseif ($atividade->status === 'Em Análise') $statusClass = 'bg-purple-100 text-purple-800';
                                            @endphp
                                            <span class="font-medium capitalize px-2 py-1 rounded-full text-xs mr-3 {{ $statusClass }}">{{ $atividade->status }}</span>
                                            <p class="font-semibold">{{ $atividade->tipoDeAtividade->nome ?? 'Regra não encontrada' }}</p>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1 ml-4">{{ $atividade->descricao_customizada }}</p>
                                        
                                        {{-- ✅ LÓGICA ATUALIZADA PARA MOSTRAR TODOS OS DADOS RELEVANTES --}}
                                        <div class="mt-2 ml-4 pl-3 border-l-2 border-gray-200 text-xs text-gray-800">
                                            @if (str_contains(strtolower($atividade->tipoDeAtividade->nome), 'aproveitamento acadêmico'))
                                                <p><strong>Média Declarada no Perfil:</strong> {{ $candidato->media_aproveitamento ?? 'N/A' }}</p>
                                            @elseif($atividade->tipoDeAtividade->unidade_medida === 'horas')
                                                <p><strong>Horas Declaradas:</strong> {{ $atividade->carga_horaria ?? 'N/A' }}</p>
                                            @elseif($atividade->tipoDeAtividade->unidade_medida === 'meses')
                                                <p><strong>Período Declarado:</strong> de {{ optional($atividade->data_inicio)->format('d/m/Y') ?? 'N/A' }} a {{ optional($atividade->data_fim)->format('d/m/Y') ?? 'N/A' }}</p>
                                            @elseif(str_contains(strtolower($atividade->tipoDeAtividade->nome), 'semestres cursados') || $atividade->tipoDeAtividade->unidade_medida === 'semestre')
                                                <p><strong>Semestres Declarados na Atividade:</strong> {{ $atividade->semestres_declarados ?? 'N/A' }}</p> {{-- ✅ AJUSTE AQUI --}}
                                            @endif
                                        </div>

                                        @if($atividade->status === 'Rejeitada' && $atividade->motivo_rejeicao)
                                            <p class="text-xs text-red-700 mt-2 p-2 bg-red-50 rounded-md"><strong>Motivo:</strong> {{ $atividade->motivo_rejeicao }}</p>
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

{{-- Conteúdo para a aba de Ações Finais --}}
<div x-show="tab === 'acoes'" x-transition x-cloak>
    <div class="mt-8 pt-6 border-t p-4 bg-gray-100 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Painel de Ações do Administrador</h3>
        <p class="text-sm text-gray-600 mb-4">Após analisar todas as informações, use os botões abaixo para alterar o status da inscrição.</p>

        {{-- Formulários para Aprovar/Rejeitar Perfil - Visível apenas se o status permitir --}}
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
        @endif

        {{-- ✅ FORMULÁRIO DE HOMOLOGAÇÃO - Visível apenas se o status for 'Aprovado' --}}
        @if ($candidato->status === 'Aprovado')
            <form action="{{ route('admin.candidatos.homologar', $candidato->id) }}" method="POST" class="w-full mt-4">
                @csrf
                <div class="bg-yellow-50 p-4 rounded-lg mt-4 mb-4">
                    <p class="font-bold text-yellow-800 mb-2">Ação: Homologar Candidato</p>
                    <div class="mb-3">
                        <label for="ato_homologacao" class="block text-sm font-medium text-gray-700">Número/Referência do Ato de Homologação <span class="text-red-500">*</span></label>
                        <input type="text" name="ato_homologacao" id="ato_homologacao" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               value="{{ old('ato_homologacao', $candidato->ato_homologacao) }}">
                        @error('ato_homologacao')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="homologacao_observacoes" class="block text-sm font-medium text-gray-700">Observações (Opcional)</label>
                        <textarea name="homologacao_observacoes" id="homologacao_observacoes" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('homologacao_observacoes', $candidato->homologacao_observacoes) }}</textarea>
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
        @else {{-- Status como 'Rejeitado', 'Inscrição Incompleta' --}}
            <div class="bg-gray-50 p-4 rounded-lg mt-4 mb-4">
                <p class="font-bold text-gray-800 mb-2">Ações Atuais:</p>
                <p class="text-sm text-gray-700">O status do candidato não permite homologação ou aprovação direta no momento.</p>
                @if ($candidato->status === 'Rejeitado')
                    <p class="text-sm text-red-600 mt-2">Candidato Rejeitado. Gerencie o recurso (futuramente).</p>
                @elseif ($candidato->status === 'Inscrição Incompleta')
                    <p class="text-sm text-yellow-600 mt-2">Candidato com inscrição incompleta. Verifique os documentos.</p>
                @endif
            </div>
        @endif

        {{-- Botão Voltar para a lista de candidatos (mantido) --}}
        <div class="text-right mt-4">
             <a href="{{ route('admin.candidatos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Voltar para a Lista</a>
        </div>
    </div>
</div> 

                </div>
            </div>
        </div>

        {{-- MODAL DE REJEIÇÃO (PARA ATIVIDADES INDIVIDUAIS) --}}
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

        {{-- ✅ NOVO MODAL DE REJEIÇÃO (PARA O PERFIL GERAL DO CANDIDATO) --}}
        <div x-show="showProfileRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
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

    </div>
</x-app-layout>