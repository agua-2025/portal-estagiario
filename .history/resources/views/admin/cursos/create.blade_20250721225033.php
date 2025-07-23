@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Cadastrar Novo Curso</h1>
                <p class="text-sm text-gray-600 mt-1">Preencha as informações para cadastrar um novo curso</p>
            </div>
            <a href="{{ route('admin.cursos.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.cursos.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Informações Básicas --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>
            
            <div class="grid grid-cols-1 gap-6">
                {{-- Nome do Curso --}}
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700">
                        Nome do Curso <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nome" 
                           id="nome" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nome') border-red-300 @enderror" 
                           value="{{ old('nome') }}" 
                           required
                           maxlength="255">
                    @error('nome')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descrição --}}
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700">
                        Descrição Curta
                    </label>
                    <textarea name="descricao" 
                              id="descricao" 
                              rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('descricao') border-red-300 @enderror"
                              maxlength="500">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Detalhes --}}
                <div>
                    <label for="detalhes" class="block text-sm font-medium text-gray-700">
                        Detalhes Completos
                    </label>
                    <textarea name="detalhes" 
                              id="detalhes" 
                              rows="5" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('detalhes') border-red-300 @enderror">{{ old('detalhes') }}</textarea>
                    @error('detalhes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ícone SVG --}}
                <div>
                    <label for="icone_svg" class="block text-sm font-medium text-gray-700">
                        Código SVG do Ícone
                    </label>
                    <textarea name="icone_svg" 
                              id="icone_svg" 
                              rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono @error('icone_svg') border-red-300 @enderror">{{ old('icone_svg') }}</textarea>
                    @error('icone_svg')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Auxílios Financeiros --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Auxílios Financeiros</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="valor_bolsa_auxilio" class="block text-sm font-medium text-gray-700">
                        Valor da Bolsa-Auxílio (R$)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           name="valor_bolsa_auxilio" 
                           id="valor_bolsa_auxilio" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('valor_bolsa_auxilio') border-red-300 @enderror" 
                           value="{{ old('valor_bolsa_auxilio') }}">
                    @error('valor_bolsa_auxilio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="valor_auxilio_transporte" class="block text-sm font-medium text-gray-700">
                        Valor do Auxílio Transporte (R$)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           name="valor_auxilio_transporte" 
                           id="valor_auxilio_transporte" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('valor_auxilio_transporte') border-red-300 @enderror" 
                           value="{{ old('valor_auxilio_transporte') }}">
                    @error('valor_auxilio_transporte')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Requisitos e Benefícios --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Requisitos e Benefícios</h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="requisitos" class="block text-sm font-medium text-gray-700">
                        Requisitos
                    </label>
                    <textarea name="requisitos" 
                              id="requisitos" 
                              rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('requisitos') border-red-300 @enderror">{{ old('requisitos') }}</textarea>
                    @error('requisitos')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="beneficios" class="block text-sm font-medium text-gray-700">
                        Benefícios Adicionais
                    </label>
                    <textarea name="beneficios" 
                              id="beneficios" 
                              rows="4" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('beneficios') border-red-300 @enderror">{{ old('beneficios') }}</textarea>
                    @error('beneficios')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Informações Complementares --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Complementares</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="carga_horaria" class="block text-sm font-medium text-gray-700">
                        Carga Horária
                    </label>
                    <input type="text" 
                           name="carga_horaria" 
                           id="carga_horaria" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('carga_horaria') border-red-300 @enderror" 
                           value="{{ old('carga_horaria') }}"
                           maxlength="50">
                    @error('carga_horaria')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="local_estagio" class="block text-sm font-medium text-gray-700">
                        Local do Estágio
                    </label>
                    <input type="text" 
                           name="local_estagio" 
                           id="local_estagio" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('local_estagio') border-red-300 @enderror" 
                           value="{{ old('local_estagio') }}"
                           maxlength="255">
                    @error('local_estagio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="flex items-center justify-end space-x-4 py-4">
            <a href="{{ route('admin.cursos.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancelar
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Salvar Curso
            </button>
        </div>
    </form>
</div>
@endsection