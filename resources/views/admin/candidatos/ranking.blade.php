<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ranking para Convocação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensagens de sucesso ou erro --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Loop para cada curso que tem candidatos homologados --}}
            @forelse ($candidatosPorCurso as $nomeCurso => $candidatos)
                <div class="mb-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        {{-- Título do curso DENTRO do card --}}
                        <h3 class="text-xl font-bold text-gray-900 border-b border-gray-200 pb-3 mb-4">
                            {{ $nomeCurso ?: 'Curso não especificado' }}
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pos.</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidato</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ação</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($candidatos as $index => $candidato)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}º</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $candidato->nome_completo }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ number_format($candidato->pontuacao_final, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                <a href="{{ route('admin.candidatos.showAtribuirVagaForm', $candidato) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700 font-bold">
                                                    Convocar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        Nenhum candidato homologado para exibir no ranking.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>