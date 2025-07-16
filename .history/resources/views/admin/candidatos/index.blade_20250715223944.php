    <x-app-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="mb-6 border-b pb-4">
                            <h2 class="text-xl font-semibold text-gray-800">Gestão de Candidatos</h2>
                            <p class="mt-1 text-sm text-gray-600">Visualize e gerencie as inscrições dos candidatos.</p>
                        </div>

                        <!-- Formulário de Busca -->
                        <form method="GET" action="{{ route('admin.candidatos.index') }}" class="mb-6">
                            <div class="flex items-center">
                                <input type="text" name="search" placeholder="Buscar por nome ou CPF..." class="w-full sm:w-1/2 rounded-md shadow-sm border-gray-300" value="{{ $search ?? '' }}">
                                <button type="submit" class="ml-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Buscar</button>
                            </div>
                        </form>

                        <!-- Tabela de Candidatos -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome Completo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CPF</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="relative px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($candidatos as $candidato)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidato->nome_completo }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->cpf }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->curso->nome ?? 'Não informado' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $candidato->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.candidatos.show', $candidato->id) }}" class="text-indigo-600 hover:text-indigo-900">Analisar Perfil</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Nenhum candidato encontrado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="mt-6">
                            {{ $candidatos->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>