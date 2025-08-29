{{-- resources/views/admin/transmissao/index.blade.php --}}
<x-app-layout>
    <div class="py-6" x-data="transmissaoPage()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- TÍTULO --}}
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Lista de Transmissão</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Envio manual por WhatsApp e e-mail
                        @if($cands->total() > 0)
                            • {{ $cands->total() }} candidato{{ $cands->total() != 1 ? 's' : '' }} encontrado{{ $cands->total() != 1 ? 's' : '' }}
                        @endif
                    </p>
                </div>
                @if($cands->hasPages())
                    <div class="text-sm text-gray-500">
                        Página {{ $cands->currentPage() }} de {{ $cands->lastPage() }}
                    </div>
                @endif
            </div>

            {{-- FLASH MESSAGES --}}
            @if(session('success') || session('error'))
                <div class="mb-4 space-y-2">
                    @if(session('success'))
                        <div class="p-3 rounded bg-green-50 text-green-700 border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="p-3 rounded bg-red-50 text-red-700 border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- FILTROS / BUSCA --}}
            <div class="bg-white border border-gray-200 rounded p-3 mb-4">
                <form method="GET" action="{{ route('admin.transmissao.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-600 mb-1">Buscar</label>
                        <input type="text" name="q" value="{{ $q }}" placeholder="Nome, CPF, e-mail ou telefone"
                               class="w-full text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="w-full sm:w-40">
                        <label class="block text-xs text-gray-600 mb-1">Status</label>
                        <select name="status" class="w-full text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500">
                            @php
                                $opts = [
                                    '' => 'Todos',
                                    'incompletos' => 'Incompleta',
                                    'em_analise'  => 'Em Análise',
                                    'aprovado'    => 'Aprovado',
                                    'homologado'  => 'Homologado',
                                    'convocado'   => 'Convocado',
                                ];
                            @endphp
                            @foreach($opts as $val => $label)
                                <option value="{{ $val }}" @selected($status === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        Buscar
                    </button>
                </form>
            </div>

            {{-- MODELOS + TEXTO --}}
            <div class="bg-white border border-gray-200 rounded p-3 mb-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end mb-3">
                    <div class="w-full sm:w-40">
                        <label class="block text-xs text-gray-600 mb-1">Modelo</label>
                        <select x-model="selectedModel" class="w-full text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500">
                            <option value="incompleto">Incompleto</option>
                            <option value="lembrete">Lembrete</option>
                            <option value="convocacao">Convocação</option>
                        </select>
                    </div>
                    <button type="button" @click="loadTemplate()" class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                        Carregar
                    </button>
                    <div class="flex-1">
                        <label class="block text-xs text-gray-600 mb-1">Assunto (e-mail)</label>
                        <input type="text" x-model="subject" placeholder="Portal do Estagiário"
                               class="w-full text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-gray-600 mb-1">Mensagem</label>
                    <textarea x-model="message"
                              class="w-full text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500"
                              rows="3"></textarea>
                    <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                        <div class="space-x-2">
                            <code>{nome}</code><code>{primeiro_nome}</code><code>{percent}</code>
                            <code>{curso}</code><code>{status}</code><code>{link_login}</code>
                        </div>
                        <button type="button" @click="copyMessage()" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs">
                            Copiar msg
                        </button>
                    </div>
                </div>
            </div>

            {{-- TABELA --}}
            <div class="bg-white border border-gray-200 rounded overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Nome</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Curso</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Telefone</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">E-mail</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Status</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-700">Último contato</th>
                                <th class="px-3 py-2 text-center font-medium text-gray-700">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($cands as $cand)
                                @php
                                    $status = $cand->status;
                                    $statusClass = match ($status) {
                                        'Inscrição Incompleta' => 'bg-yellow-100 text-yellow-700',
                                        'Em Análise'           => 'bg-blue-100 text-blue-700',
                                        'Aprovado'             => 'bg-green-100 text-green-700',
                                        'Homologado'           => 'bg-purple-100 text-purple-700',
                                        'Convocado'            => 'bg-gray-700 text-white',
                                        default                => 'bg-gray-100 text-gray-600',
                                    };
                                    $phoneDigits = preg_replace('/\D+/', '', (string) $cand->telefone);
                                    $hasPhone = !empty($phoneDigits);
                                    $hasEmail = (bool) optional($cand->user)->email;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 font-medium text-gray-900">
                                        {{ $cand->nome_completo }}
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">
                                        {{ $cand->curso->nome ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">
                                        {{ $cand->telefone ?: '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">
                                        {{ optional($cand->user)->email ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2">
                                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">
                                        @if($cand->last_contacted_at)
                                            <div class="text-xs">{{ $cand->last_contacted_at->format('d/m/Y H:i') }}</div>
                                            @if($cand->last_contact_via)
                                                <div class="text-xs text-gray-500">{{ ucfirst($cand->last_contact_via) }}</div>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center justify-center gap-1">
                                            {{-- WhatsApp --}}
                                            @if($hasPhone)
                                                <a :href="waUrl('{{ route('admin.transmissao.whatsapp', $cand) }}')"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                    WhatsApp
                                                </a>
                                            @else
                                                <span class="px-3 py-1 bg-gray-200 text-gray-400 text-xs rounded cursor-not-allowed" title="Sem telefone">
                                                    WhatsApp
                                                </span>
                                            @endif

                                            {{-- E-mail --}}
                                            @if($hasEmail)
                                                <form method="POST" action="{{ route('admin.transmissao.email', $cand) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="subject" :value="subject">
                                                    <input type="hidden" name="text"    :value="message">
                                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                                        E-mail
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-3 py-1 bg-gray-200 text-gray-400 text-xs rounded cursor-not-allowed" title="Sem e-mail">
                                                    E-mail
                                                </span>
                                            @endif

                                            {{-- Copiar (pra colar no WhatsApp Web manualmente, se quiser) --}}
                                            <button type="button" @click="copyMessage()" class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                                                Copiar msg
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-8 text-center text-gray-500">Nenhum candidato encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if($cands->hasPages())
                    <div class="px-3 py-3 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Mostrando {{ $cands->firstItem() ?? 0 }} até {{ $cands->lastItem() ?? 0 }} de {{ $cands->total() }} resultados
                            </div>
                            <div class="flex items-center space-x-1">
                                {{ $cands->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    @if($cands->total() > 0)
                        <div class="px-3 py-2 border-t border-gray-200 bg-gray-50 text-sm text-gray-600 text-center">
                            {{ $cands->total() }} candidato{{ $cands->total() != 1 ? 's' : '' }} encontrado{{ $cands->total() != 1 ? 's' : '' }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Alpine store / helpers --}}
    <script>
        function transmissaoPage() {
            return {
                templates: @json($templates),
                selectedModel: 'incompleto',
                subject: 'Portal do Estagiário',
                message: @json($templates['incompleto'] ?? 'Olá {primeiro_nome}!'),

                loadTemplate() {
                    const tpl = this.templates?.[this.selectedModel] || '';
                    this.message = tpl;
                },

                copyMessage() {
                    navigator.clipboard.writeText(this.message || '');
                },

                // monta URL para o route de whatsapp com ?text=...
                waUrl(base) {
                    const u = new URL(base, window.location.origin);
                    u.searchParams.set('text', this.message || '');
                    return u.toString();
                },
            }
        }
    </script>
</x-app-layout>
