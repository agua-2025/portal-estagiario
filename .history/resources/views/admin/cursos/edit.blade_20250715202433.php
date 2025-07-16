@extends('layouts.admin')

@section('content')
<div class="px-4 py-8">
    {{-- Container Principal --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        {{-- Cabeçalho --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Editar Curso</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $curso->nome }}</p>
                </div>
                <a href="{{ route('admin.cursos.index') }}" 
                   class="text-gray-600 hover:text-gray-800 transition duration-150 ease-in-out">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Formulário --}}
        <form action="{{ route('admin.cursos.update', $curso) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Nome do Curso --}}
            <div>
                <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nome do Curso
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nome" 
                       id="nome" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('nome') border-red-500 @enderror" 
                       value="{{ old('nome', $curso->nome) }}" 
                       required>
                @error('nome')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descrição Curta --}}
            <div>
                <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                    Descrição Curta
                </label>
                <textarea name="descricao" 
                          id="descricao" 
                          rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('descricao') border-red-500 @enderror"
                          placeholder="Breve descrição do curso">{{ old('descricao', $curso->descricao) }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Detalhes Completos --}}
            <div>
                <label for="detalhes" class="block text-sm font-semibold text-gray-700 mb-2">
                    Detalhes Completos do Curso
                </label>
                <textarea name="detalhes" 
                          id="detalhes" 
                          rows="6" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('detalhes') border-red-500 @enderror"
                          placeholder="Descrição detalhada do curso, objetivos, metodologia, etc.">{{ old('detalhes', $curso->detalhes) }}</textarea>
                @error('detalhes')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Seção de Valores --}}
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Auxílios Financeiros
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="valor_bolsa_auxilio" class="block text-sm font-semibold text-gray-700 mb-2">
                            Valor da Bolsa-Auxílio (R$)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               name="valor_bolsa_auxilio" 
                               id="valor_bolsa_auxilio" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('valor_bolsa_auxilio') border-red-500 @enderror" 
                               value="{{ old('valor_bolsa_auxilio', $curso->valor_bolsa_auxilio) }}"
                               placeholder="0,00">
                        @error('valor_bolsa_auxilio')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="valor_auxilio_transporte" class="block text-sm font-semibold text-gray-700 mb-2">
                            Valor do Auxílio Transporte (R$)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               name="valor_auxilio_transporte" 
                               id="valor_auxilio_transporte" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('valor_auxilio_transporte') border-red-500 @enderror" 
                               value="{{ old('valor_auxilio_transporte', $curso->valor_auxilio_transporte) }}"
                               placeholder="0,00">
                        @error('valor_auxilio_transporte')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Requisitos --}}
            <div>
                <label for="requisitos" class="block text-sm font-semibold text-gray-700 mb-2">
                    Requisitos
                </label>
                <textarea name="requisitos" 
                          id="requisitos" 
                          rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('requisitos') border-red-500 @enderror"
                          placeholder="Escolaridade, idade, experiência, etc.">{{ old('requisitos', $curso->requisitos) }}</textarea>
                @error('requisitos')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Benefícios Adicionais --}}
            <div>
                <label for="beneficios" class="block text-sm font-semibold text-gray-700 mb-2">
                    Benefícios Adicionais
                </label>
                <textarea name="beneficios" 
                          id="beneficios" 
                          rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('beneficios') border-red-500 @enderror"
                          placeholder="Certificado, material didático, alimentação, etc.">{{ old('beneficios', $curso->beneficios) }}</textarea>
                @error('beneficios')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Informações Complementares --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="carga_horaria" class="block text-sm font-semibold text-gray-700 mb-2">
                        Carga Horária
                    </label>
                    <input type="text" 
                           name="carga_horaria" 
                           id="carga_horaria" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('carga_horaria') border-red-500 @enderror" 
                           value="{{ old('carga_horaria', $curso->carga_horaria) }}"
                           placeholder="Ex: 40h semanais">
                    @error('carga_horaria')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="local_estagio" class="block text-sm font-semibold text-gray-700 mb-2">
                        Local do Estágio
                    </label>
                    <input type="text" 
                           name="local_estagio" 
                           id="local_estagio" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('local_estagio') border-red-500 @enderror" 
                           value="{{ old('local_estagio', $curso->local_estagio) }}"
                           placeholder="Ex: Prefeitura Municipal">
                    @error('local_estagio')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Botões de Ação --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.cursos.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Atualizar Curso
                </button>
            </div>
        </form>
    </div>
</div>
@endsection