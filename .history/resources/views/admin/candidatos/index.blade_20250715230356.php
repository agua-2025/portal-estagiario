<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">

                    <div class="mb-4 border-b pb-3">
                        <h2 class="text-lg font-semibold text-gray-800">Gestão de Candidatos</h2>
                        <p class="text-sm text-gray-600">Visualize e gerencie as inscrições dos candidatos.</p>
                    </div>

                    <!-- Formulário de Busca -->
                    <form method="GET" action="{{ route('admin.candidatos.index') }}" class="mb-4">
                        <div class="flex items-center gap-3">
                            <input type="text" name="search" placeholder="Buscar por nome ou CPF..." 
                                   class="flex-1 max-w-md px-3 py-2 text-sm rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                   value="{{ $search ?? '' }}">
                            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Buscar
                            </button>
                        </div>
                    </form>

                    <!-- Tabela de Candidatos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome Completo</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="relative px-4 py-2">
                                        <span class="sr-only">Ações</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($candidatos as $candidato)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $candidato->nome_completo }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $candidato->cpf }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $candidato->curso->nome ?? 'Não informado' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $candidato->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.candidatos.show', $candidato->id) }}" 
                                               class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                                Analisar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                            Nenhum candidato encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $candidatos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>