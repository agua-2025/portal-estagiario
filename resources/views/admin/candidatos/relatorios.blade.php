<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Relatórios de Candidatos</h2>

                    {{-- PAINEL DE FILTROS --}}
                    <div class="bg-gray-100 p-6 rounded-lg mb-8">
                        <form method="GET" action="{{ route('admin.candidatos.relatorios') }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <label for="curso_id" class="block text-sm font-medium text-gray-700">Curso</label>
                                    <select name="curso_id" id="curso_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Todos os Cursos</option>
                                        @foreach($cursos as $id => $nome)
                                            <option value="{{ $id }}" {{ (isset($selectedFilters['curso_id']) && $selectedFilters['curso_id'] == $id) ? 'selected' : '' }}>
                                                {{ $nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Fase (Status)</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Todos os Status</option>
                                        @foreach($statusOptions as $id => $nome)
                                            <option value="{{ $id }}" {{ (isset($selectedFilters['status']) && $selectedFilters['status'] == $id) ? 'selected' : '' }}>
                                                {{ $nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="classificacao" class="block text-sm font-medium text-gray-700">Classificação</label>
                                    <select name="classificacao" id="classificacao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Todas as Classificações</option>
                                        @foreach($classificacaoOptions as $id => $nome)
                                            <option value="{{ $id }}" {{ (isset($selectedFilters['classificacao']) && $selectedFilters['classificacao'] == $id) ? 'selected' : '' }}>
                                                {{ $nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-end space-x-2">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold text-sm">Aplicar Filtros</button>
                                    <a href="{{ route('admin.candidatos.relatorios') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">Limpar</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- TABELA DE RESULTADOS --}}
                    <div class="overflow-x-auto shadow-sm sm:rounded-lg">
                        <div class="flex justify-between items-center p-4 bg-white border-b border-gray-200">
                             <h3 class="text-xl font-bold text-gray-800">Candidatos Encontrados ({{ $candidatos->count() }})</h3>
                            <button class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">Exportar para Excel</button>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instituição</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($candidatos as $candidato)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidato->nome_completo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->cpf }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->curso->nome ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->instituicao->nome ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = 'text-gray-800 bg-gray-100';
                                                if ($candidato->status === 'Aprovado' || $candidato->status === 'Homologado') {
                                                    $statusClass = 'text-green-800 bg-green-100';
                                                } elseif ($candidato->status === 'Rejeitado') {
                                                    $statusClass = 'text-red-800 bg-red-100';
                                                } elseif ($candidato->status === 'Em Análise') {
                                                    $statusClass = 'text-blue-800 bg-blue-100';
                                                }
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $candidato->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.candidatos.show', $candidato->id) }}" class="text-indigo-600 hover:text-indigo-900">Ver Perfil</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum candidato encontrado com os filtros selecionados.</td>
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