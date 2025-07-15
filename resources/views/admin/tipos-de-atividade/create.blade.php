<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cadastrar Nova Regra de Pontuação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- ✅ LÓGICA UNIFICADA E CORRIGIDA --}}
                <div class="p-6 text-gray-900" x-data="{ 
                    unidadeMedida: '{{ old('unidade_medida', 'fixo') }}', // Padrão para 'fixo'
                    nomeRegra: '{{ old('nome', '') }}'
                }">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Cadastrar Nova Regra de Pontuação
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">Defina aqui as atividades e como elas serão pontuadas.</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Opa! Algo deu errado.</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.tipos-de-atividade.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <!-- Nome da Regra -->
                            <div>
                                <label for="nome" class="block font-medium text-sm text-gray-700">Nome da Regra</label>
                                <input id="nome" name="nome" type="text" x-model="nomeRegra" value="{{ old('nome') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required autofocus>
                            </div>

                            <!-- Descrição -->
                            <div>
                                <label for="descricao" class="block font-medium text-sm text-gray-700">Descrição (Opcional)</label>
                                <textarea id="descricao" name="descricao" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('descricao') }}</textarea>
                            </div>

                            <!-- Tipo de Cálculo -->
                            <div>
                                <label for="unidade_medida" class="block font-medium text-sm text-gray-700">Tipo de Cálculo</label>
                                <select id="unidade_medida" name="unidade_medida" x-model="unidadeMedida" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="fixo">Pontuação Fixa</option>
                                    <option value="horas">Por Carga Horária</option>
                                    <option value="meses">Por Duração (meses)</option>
                                    <option value="semestre">Por Semestre</option> {{-- ✅ ADICIONADO AQUI --}}
                                </select>
                            </div>
                            
                            <!-- Campos Dinâmicos -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- Campo de Pontos --}}
                                <div>
                                    <label for="pontos_por_unidade" class="block font-medium text-sm text-gray-700" 
                                            x-text="unidadeMedida === 'fixo' ? 'Pontos' : 'Pontos por Unidade'"></label>
                                    <input id="pontos_por_unidade" name="pontos_por_unidade" type="number" step="0.01" value="{{ old('pontos_por_unidade') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>

                                {{-- Divisor (aparece para horas/meses/semestre OU para a regra especial de média) --}}
                                {{-- ✅ AJUSTE: Incluindo 'semestre' na condição de visibilidade --}}
                                <div x-show="unidadeMedida === 'horas' || unidadeMedida === 'meses' || unidadeMedida === 'semestre' || (unidadeMedida === 'fixo' && nomeRegra.toLowerCase().includes('aproveitamento acadêmico'))" x-transition>
                                    <label for="divisor_unidade" class="block font-medium text-sm text-gray-700"
                                            x-text="unidadeMedida === 'fixo' ? 'Nota de Corte (Média Mínima)' : 'A cada ' + (unidadeMedida === 'semestre' ? 'semestre(s)' : unidadeMedida)"></label>
                                    <input id="divisor_unidade" name="divisor_unidade" type="number" step="0.01" value="{{ old('divisor_unidade') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="text-xs text-gray-500 mt-1" x-show="unidadeMedida !== 'fixo'">Ex: 1 ponto a cada **30** horas.</p>
                                </div>

                                {{-- Pontuação Máxima (aparece para horas/meses/semestre) --}}
                                {{-- ✅ AJUSTE: Incluindo 'semestre' na condição de visibilidade --}}
                                <div x-show="unidadeMedida === 'horas' || unidadeMedida === 'meses' || unidadeMedida === 'semestre'" x-transition>
                                    <label for="pontuacao_maxima" class="block font-medium text-sm text-gray-700">Pontuação Máxima (Opcional)</label>
                                    <input id="pontuacao_maxima" name="pontuacao_maxima" type="number" value="{{ old('pontuacao_maxima') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end mt-6 pt-6 border-t">
                            <a href="{{ route('admin.tipos-de-atividade.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Salvar Regra
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>