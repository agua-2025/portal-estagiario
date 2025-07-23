@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">Cadastrar Novo Curso</h1>
            <p class="mt-1 text-sm text-gray-600">Preencha as informações para cadastrar um novo curso</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('admin.cursos.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Nome do Curso --}}
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-900 mb-2">
                        Nome do Curso
                    </label>
                    <input type="text" 
                           name="nome" 
                           id="nome" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-300 @enderror" 
                           value="{{ old('nome') }}" 
                           required
                           maxlength="255">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descrição Curta --}}
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-900 mb-2">
                        Descrição (Opcional)
                    </label>
                    <textarea name="descricao" 
                              id="descricao" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('descricao') border-red-300 @enderror"
                              maxlength="500">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Detalhes Completos --}}
                <div>
                    <label for="detalhes" class="block text-sm font-medium text-gray-900 mb-2">
                        Detalhes Completos
                    </label>
                    <textarea name="detalhes" 
                              id="detalhes" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('detalhes') border-red-300 @enderror">{{ old('detalhes') }}</textarea>
                    @error('detalhes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ícone SVG --}}
                <div>
                    <label for="icone_svg" class="block text-sm font-medium text-gray-900 mb-2">
                        Código SVG do Ícone
                    </label>
                    <textarea name="icone_svg" 
                              id="icone_svg" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('icone_svg') border-red-300 @enderror">{{ old('icone_svg') }}</textarea>
                    @error('icone_svg')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Auxílios Financeiros --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="valor_bolsa_auxilio" class="block text-sm font-medium text-gray-900 mb-2">
                            Bolsa-Auxílio (R$)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               min="0"
                               name="valor_bolsa_auxilio" 
                               id="valor_bolsa_auxilio" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor_bolsa_auxilio') border-red-300 @enderror" 
                               value="{{ old('valor_bolsa_auxilio') }}">
                        @error('valor_bolsa_auxilio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="valor_auxilio_transporte" class="block text-sm font-medium text-gray-900 mb-2">
                            Auxílio Transporte (R$)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               min="0"
                               name="valor_auxilio_transporte" 
                               id="valor_auxilio_transporte" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor_auxilio_transporte') border-red-300 @enderror" 
                               value="{{ old('valor_auxilio_transporte') }}">
                        @error('valor_auxilio_transporte')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Requisitos --}}
                <div>
                    <label for="requisitos" class="block text-sm font-medium text-gray-900 mb-2">
                        Requisitos
                    </label>
                    <textarea name="requisitos" 
                              id="requisitos" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('requisitos') border-red-300 @enderror">{{ old('requisitos') }}</textarea>
                    @error('requisitos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Benefícios --}}
                <div>
                    <label for="beneficios" class="block text-sm font-medium text-gray-900 mb-2">
                        Benefícios Adicionais
                    </label>
                    <textarea name="beneficios" 
                              id="beneficios" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('beneficios') border-red-300 @enderror">{{ old('beneficios') }}</textarea>
                    @error('beneficios')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Informações Complementares --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="carga_horaria" class="block text-sm font-medium text-gray-900 mb-2">
                            Carga Horária
                        </label>
                        <input type="text" 
                               name="carga_horaria" 
                               id="carga_horaria" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('carga_horaria') border-red-300 @enderror" 
                               value="{{ old('carga_horaria') }}"
                               maxlength="50">
                        @error('carga_horaria')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="local_estagio" class="block text-sm font-medium text-gray-900 mb-2">
                            Local do Estágio
                        </label>
                        <input type="text" 
                               name="local_estagio" 
                               id="local_estagio" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('local_estagio') border-red-300 @enderror" 
                               value="{{ old('local_estagio') }}"
                               maxlength="255">
                        @error('local_estagio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.cursos.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Salvar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection