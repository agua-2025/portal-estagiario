<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Gestão de Regras de Pontuação
                        </h2>
                        <a href="{{ route('admin.tipos-de-atividade.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Nova Regra
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif


                    <!-- TABELA DE REGRAS, MAIS COMPLETA E INTELIGENTE -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome da Regra</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo de Cálculo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Regra de Pontuação</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pontuação Máxima</th>
                                    <th class="relative px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($atividades as $atividade)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $atividade->nome }}</td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $atividade->unidade_medida }}</td>
                                        
                                        {{-- ✅ LÓGICA ATUALIZADA PARA MOSTRAR A NOTA DE CORTE --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if (str_contains(strtolower($atividade->nome), 'aproveitamento acadêmico') && $atividade->unidade_medida == 'fixo')
                                                {{ $atividade->pontos_por_unidade }} pontos (se média >= {{ $atividade->divisor_unidade ?? 'N/A' }})
                                            @elseif($atividade->unidade_medida == 'fixo')
                                                {{ $atividade->pontos_por_unidade }} pontos
                                            @else
                                                {{ $atividade->pontos_por_unidade }} ponto(s) a cada {{ $atividade->divisor_unidade }} {{ $atividade->unidade_medida }}
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $atividade->pontuacao_maxima ?? 'N/A' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.tipos-de-atividade.edit', $atividade->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</a>
                                            <form class="inline-block" method="POST" action="{{ route('admin.tipos-de-atividade.destroy', $atividade->id) }}" onsubmit="return confirm('Tem certeza que deseja apagar esta regra?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Apagar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Nenhuma regra de pontuação cadastrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>