<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ranking para Convocação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            <!-- Controles de Filtro -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    placeholder="Buscar candidato..." 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    id="searchInput"
                                >
                                <div class="relative flex-1">
                                    <select class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none" id="cursoFilter">
                                        <option value="">Todos os cursos</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-none">
                            <button onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition-colors border border-gray-300">
                                Imprimir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 border-b-2 border-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-20">Pos.</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Candidato</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-32">Pontuação</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-32">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($candidatosPorCurso as $nomeCurso => $candidatos)
                                {{-- Linha de Cabeçalho do Curso --}}
                                <tr class="bg-blue-50 border-t border-blue-100">
                                    <td colspan="4" class="px-6 py-2 text-sm font-semibold text-blue-800 border-b border-blue-100">
                                        {{ $nomeCurso ?: 'Curso não especificado' }}
                                    </td>
                                </tr>

                                {{-- Linhas dos Candidatos do Curso --}}
                                @foreach ($candidatos as $index => $candidato)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 candidate-row border-b border-gray-100" data-curso="{{ $nomeCurso }}">
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            @if ($index == 0)
                                                <div class="w-6 h-6 bg-amber-500 text-white text-xs font-bold rounded-full flex items-center justify-center shadow-sm">
                                                    {{ $index + 1 }}
                                                </div>
                                            @else
                                                <div class="w-6 h-6 bg-gray-300 text-gray-700 text-xs font-semibold rounded-full flex items-center justify-center">
                                                    {{ $index + 1 }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 candidate-name">{{ $candidato->nome_completo }}</div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900 font-mono">{{ number_format($candidato->pontuacao_final, 2, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-center">
                                            @if ($index == 0)
                                                <a href="{{ route('admin.candidatos.showAtribuirVagaForm', $candidato) }}" 
                                                   class="inline-flex items-center justify-center px-3 py-1.5 w-20 bg-blue-600 border border-blue-600 rounded text-xs font-medium text-white hover:bg-blue-700 hover:border-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200">
                                                    Convocar
                                                </a>
                                            @else
                                                <span class="inline-flex items-center justify-center px-3 py-1.5 w-20 bg-gray-100 border border-gray-200 rounded text-xs font-medium text-gray-500 cursor-not-allowed" 
                                                      title="Aguardando a convocação dos candidatos com maior pontuação.">
                                                    Aguardando
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-400">
                                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-sm text-gray-500">Nenhum candidato homologado para exibir no ranking.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bg-gray-50 { background: white !important; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const cursoFilter = document.getElementById('cursoFilter');
            
            // Popular filtro de cursos
            const cursos = new Set();
            document.querySelectorAll('.candidate-row').forEach(row => {
                const curso = row.getAttribute('data-curso');
                if (curso) cursos.add(curso);
            });
            
            cursos.forEach(curso => {
                const option = document.createElement('option');
                option.value = curso;
                option.textContent = curso;
                cursoFilter.appendChild(option);
            });

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const cursoSelecionado = cursoFilter.value;
                
                const rows = document.querySelectorAll('.candidate-row');
                const headers = document.querySelectorAll('tr.bg-slate-50');
                
                let cursosVisiveis = new Set();
                
                rows.forEach(row => {
                    const candidateName = row.querySelector('.candidate-name').textContent.toLowerCase();
                    const curso = row.getAttribute('data-curso');
                    
                    let mostrar = true;
                    
                    if (searchTerm && !candidateName.includes(searchTerm)) {
                        mostrar = false;
                    }
                    
                    if (cursoSelecionado && curso !== cursoSelecionado) {
                        mostrar = false;
                    }
                    
                    row.style.display = mostrar ? '' : 'none';
                    
                    if (mostrar) {
                        cursosVisiveis.add(curso);
                    }
                });
                
                headers.forEach(header => {
                    const cursoHeader = header.textContent.trim();
                    let mostrarHeader = false;
                    
                    cursosVisiveis.forEach(curso => {
                        if (curso === cursoHeader || cursosVisiveis.size === 0) {
                            mostrarHeader = true;
                        }
                    });
                    
                    header.style.display = mostrarHeader ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterTable);
            cursoFilter.addEventListener('change', filterTable);
        });
    </script>
</x-app-layout>