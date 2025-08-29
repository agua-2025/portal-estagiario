{{-- resources/views/admin/transmissao/index.blade.php --}}
<x-app-layout>
    <div class="py-6 bg-gray-50 min-h-screen" x-data="transmissaoPage()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- TÍTULO --}}
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-medium text-gray-900">Lista de Transmissão</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Envio manual por WhatsApp e e-mail
                        @if($cands->total() > 0)
                            • {{ $cands->total() }} candidato{{ $cands->total() != 1 ? 's' : '' }}
                        @endif
                    </p>
                </div>
                @if($cands->hasPages())
                    <div class="text-sm text-gray-500">
                        {{ $cands->currentPage() }}/{{ $cands->lastPage() }}
                    </div>
                @endif
            </div>

            {{-- FLASH MESSAGES --}}
            @if(session('success') || session('error'))
                <div class="mb-4 space-y-2">
                    @if(session('success'))
                        <div class="p-3 rounded bg-green-50 text-green-800 border border-green-200 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="p-3 rounded bg-red-50 text-red-800 border border-red-200 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- CONTROLES COMPACTOS --}}
            <div class="bg-white border border-gray-200 rounded p-4 mb-4 shadow-sm">
                {{-- Linha 1: Busca + Filtro --}}
                <form method="GET" action="{{ route('admin.transmissao.index') }}" class="flex gap-3 mb-4">
                    <div class="flex-1">
                        <input type="text" name="q" value="{{ $q }}" placeholder="Buscar candidato..."
                               class="w-full h-9 text-sm px-3 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <select name="status" class="w-32 h-9 text-sm px-3 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
                    <button type="submit" class="px-4 h-9 text-sm rounded bg-gray-900 text-white hover:bg-gray-800">
                        Buscar
                    </button>
                </form>

                {{-- Linha 2: Modelo + Assunto --}}
                <div class="flex gap-3 mb-3">
                    <select x-model="selectedModel" class="w-32 h-9 text-sm px-3 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="incompleto">Incompleto</option>
                        <option value="lembrete">Lembrete</option>
                        <option value="convocacao">Convocação</option>
                    </select>
                    <button type="button" @click="loadTemplate()" class="px-4 h-9 text-sm rounded bg-gray-900 text-white hover:bg-gray-800">
                        Carregar
                    </button>
                    <input type="text" x-model="subject" placeholder="Assunto do e-mail" class="flex-1 h-9 text-sm px-3 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                {{-- Linha 3: Mensagem --}}
                <div class="mb-3">
                    <textarea x-model="message" rows="3" placeholder="Digite a mensagem..."
                              class="w-full text-sm px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>

                {{-- Linha 4: Variables + Copiar --}}
                <div class="flex items-center justify-between text-xs border-t border-gray-100 pt-3">
                    <div class="flex gap-1">
                        <code class="bg-gray-100 px-2 py-1 rounded">{nome}</code>
                        <code class="bg-gray-100 px-2 py-1 rounded">{primeiro_nome}</code>
                        <code class="bg-gray-100 px-2 py-1 rounded">{curso}</code>
                        <code class="bg-gray-100 px-2 py-1 rounded">{status}</code>
                        <code class="bg-gray-100 px-2 py-1 rounded">{link_login}</code>
                    </div>
                    <button type="button" @click="copyMessage()" class="text-sm px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-gray-700">
                        Copiar
                    </button>
                </div>
            </div>

            {{-- TABELA --}}
            <div class="bg-white border border-gray-200 rounded overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase border-r border-gray-200">Nome</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase border-r border-gray-200">Curso</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase border-r border-gray-200">Telefone</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase border-r border-gray-200">E-mail</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase border-r border-gray-200">Status</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase border-r border-gray-200">Último contato</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-700 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($cands as $cand)
                                @php
                                    $status = $cand->status;
                                    $statusClass = match ($status) {
                                        'Inscrição Incompleta' => 'bg-yellow-100 text-yellow-800',
                                        'Em Análise'           => 'bg-blue-100 text-blue-800',
                                        'Aprovado'             => 'bg-green-100 text-green-800',
                                        'Homologado'           => 'bg-purple-100 text-purple-800',
                                        'Convocado'            => 'bg-gray-800 text-white',
                                        default                => 'bg-gray-100 text-gray-700',
                                    };
                                    $phoneDigits = preg_replace('/\D+/', '', (string) $cand->telefone);
                                    $hasPhone = !empty($phoneDigits);
                                    $hasEmail = (bool) optional($cand->user)->email;
                                @endphp
                                <tr class="border-t border-gray-100 hover:bg-gray-50">
                                    <td class="px-3 py-2 text-sm font-medium text-gray-900 border-r border-gray-100">
                                        {{ $cand->nome_completo }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-600 border-r border-gray-100">
                                        {{ $cand->curso->nome ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-600 border-r border-gray-100">
                                        {{ $cand->telefone ?: '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-600 border-r border-gray-100">
                                        {{ optional($cand->user)->email ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 border-r border-gray-100">
                                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-600 border-r border-gray-100">
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
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- WhatsApp --}}
                                            @if($hasPhone)
                                                <a :href="waUrl('{{ route('admin.transmissao.whatsapp', $cand) }}')"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="w-8 h-8 rounded bg-green-600 hover:bg-green-700 text-white flex items-center justify-center"
                                                   title="WhatsApp">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="w-8 h-8 rounded bg-gray-200 text-gray-400 flex items-center justify-center cursor-not-allowed"
                                                      title="Sem telefone">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                </span>
                                            @endif

                                            {{-- E-mail --}}
                                            @if($hasEmail)
                                                <form method="POST" action="{{ route('admin.transmissao.email', $cand) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="subject" :value="subject">
                                                    <input type="hidden" name="text"    :value="message">
                                                    <button type="submit"
                                                            class="w-8 h-8 rounded bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center"
                                                            title="E-mail">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="w-8 h-8 rounded bg-gray-200 text-gray-400 flex items-center justify-center cursor-not-allowed"
                                                      title="Sem e-mail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                </span>
                                            @endif

                                            {{-- Copiar --}}
                                            <button type="button" @click="copyMessage()"
                                                    class="w-8 h-8 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center"
                                                    title="Copiar mensagem">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-6 text-center text-sm text-gray-500">Nenhum candidato encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if($cands->hasPages())
                    <div class="px-3 py-2 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between text-sm">
                            <div class="text-gray-600">
                                {{ $cands->firstItem() ?? 0 }} - {{ $cands->lastItem() ?? 0 }} de {{ $cands->total() }}
                            </div>
                            <div class="flex items-center space-x-1">
                                {{ $cands->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    @if($cands->total() > 0)
                        <div class="px-3 py-2 border-t border-gray-200 bg-gray-50 text-sm text-gray-600 text-center">
                            {{ $cands->total() }} candidato{{ $cands->total() != 1 ? 's' : '' }}
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
                templates: @json($templates ?? []),
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

                waUrl(base) {
                    const u = new URL(base, window.location.origin);
                    u.searchParams.set('text', this.message || '');
                    return u.toString();
                },
            }
        }
    </script>
</x-app-layout>