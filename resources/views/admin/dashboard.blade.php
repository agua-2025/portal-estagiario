<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Painel do Administrador
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
                    <!-- Seção de Estatísticas (minimalista, 1 linha no desktop) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="text-xs font-medium text-gray-500 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>
            Total de Inscrições
            </div>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalInscricoes }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="text-xs font-medium text-gray-500 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
            Inscrições Incompletas
            </div>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $incompletas }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="text-xs font-medium text-gray-500 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
            Aguardando Análise
            </div>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $aguardandoAnalise }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="text-xs font-medium text-gray-500 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
            Homologados
            </div>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $homologados }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="text-xs font-medium text-gray-500 flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
            Convocados
            </div>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $convocados }}</p>
        </div>
        </div>

            <!-- Secção de Ações Pendentes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Inscrições Pendentes de Análise</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidato</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Inscrição</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Analisar</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ultimasPendentes as $candidato)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidato->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->curso->nome ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.candidatos.show', $candidato->id) }}" class="text-indigo-600 hover:text-indigo-900">Analisar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Nenhuma inscrição pendente de análise. Bom trabalho!
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