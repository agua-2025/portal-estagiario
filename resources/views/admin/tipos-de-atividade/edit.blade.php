<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Regra de Pontuação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- ✅ ADICIONADO O x-data PARA CONTROLAR OS CAMPOS DINÂMICOS --}}
                <div class="p-6 text-gray-900" x-data="{ 
                    unidadeMedida: '{{ old('unidade_medida', $atividade->unidade_medida) }}',
                    nomeRegra: '{{ old('nome', $atividade->nome) }}'
                }">

                    <h3 class="text-lg font-semibold mb-4">Editando: <span x-text="nomeRegra"></span></h3>

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

                    <form method="POST" action="{{ route('admin.tipos-de-atividade.update', $atividade->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nome da Regra --}}
                            <div class="md:col-span-2">
                                <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Regra</label>
                                <input type="text" name="nome" id="nome" x-model="nomeRegra" value="{{ old('nome', $atividade->nome) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            {{-- Descrição --}}
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição (Opcional)</label>
                                <textarea name="descricao" id="descricao" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('descricao', $atividade->descricao) }}</textarea>
                            </div>

                            {{-- Unidade de Medida --}}
                            <div>
                                <label for="unidade_medida" class="block text-sm font-medium text-gray-700">Unidade de Medida</label>
                                <select name="unidade_medida" id="unidade_medida" x-model="unidadeMedida" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="horas">Horas</option>
                                    <option value="meses">Meses</option>
                                    <option value="fixo">Fixo</option>
                                    <option value="semestre">Semestre</option> {{-- ✅ ADICIONADO AQUI --}}
                                </select>
                            </div>

                            {{-- Pontos por Unidade --}}
                            <div>
                                <label for="pontos_por_unidade" class="block text-sm font-medium text-gray-700">Pontos</label>
                                <input type="number" step="0.01" name="pontos_por_unidade" id="pontos_por_unidade" value="{{ old('pontos_por_unidade', $atividade->pontos_por_unidade) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            {{-- ✅ CAMPO DINÂMICO INTELIGENTE --}}
                            {{-- Aparece para Horas, Meses, Semestre E "Número de semestres cursados" --}}
                            {{-- AJUSTE: Agora também aparece se unidadeMedida for 'semestre' --}}
                            <div x-show="unidadeMedida === 'horas' || unidadeMedida === 'meses' || unidadeMedida === 'semestre' || nomeRegra.toLowerCase().includes('semestres cursados')" x-transition>
                                <label for="divisor_unidade_tempo" class="block text-sm font-medium text-gray-700">Divisor da Unidade (Ex: a cada 30 horas)</label>
                                <input type="number" name="divisor_unidade" id="divisor_unidade_tempo" value="{{ old('divisor_unidade', $atividade->divisor_unidade) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            {{-- Aparece para a regra especial de Aproveitamento Acadêmico --}}
                            <div x-show="unidadeMedida === 'fixo' && nomeRegra.toLowerCase().includes('aproveitamento acadêmico')" x-transition>
                                <label for="divisor_unidade_media" class="block text-sm font-medium text-gray-700">Nota de Corte (Média Mínima)</label>
                                <input type="number" step="0.01" name="divisor_unidade" id="divisor_unidade_media" value="{{ old('divisor_unidade', $atividade->divisor_unidade) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            {{-- ✅ Pontuação Máxima (Aparece para Horas, Meses, Semestre, "Número de semestres cursados") --}}
                            {{-- AJUSTE: Agora também aparece se unidadeMedida for 'semestre' --}}
                            <div x-show="unidadeMedida === 'horas' || unidadeMedida === 'meses' || unidadeMedida === 'semestre' || nomeRegra.toLowerCase().includes('semestres cursados')" x-transition>
                                <label for="pontuacao_maxima" class="block text-sm font-medium text-gray-700">Pontuação Máxima (Opcional)</label>
                                <input type="number" name="pontuacao_maxima" id="pontuacao_maxima" value="{{ old('pontuacao_maxima', $atividade->pontuacao_maxima) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t flex justify-end space-x-4">
                            <a href="{{ route('admin.tipos-de-atividade.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>