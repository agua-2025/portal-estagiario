@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-light text-gray-900">Novo Curso</h1>
                    <p class="mt-2 text-sm text-gray-600">Preencha as informações para cadastrar um novo curso</p>
                </div>
                <a href="{{ route('admin.cursos.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <form action="{{ route('admin.cursos.store') }}" method="POST" class="divide-y divide-gray-100">
                @csrf

                {{-- Seção: Informações Básicas --}}
                <div class="p-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Informações Básicas
                    </h2>

                    <div class="space-y-6">
                        {{-- Nome do Curso --}}
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-900 mb-2">
                                Nome do Curso <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   name="nome" 
                                   id="nome" 
                                   class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('nome') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   value="{{ old('nome') }}" 
                                   required
                                   maxlength="255"
                                   placeholder="Ex: Curso de Desenvolvimento Web">
                            @error('nome')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descrição Curta --}}
                        <div>
                            <label for="descricao" class="block text-sm font-medium text-gray-900 mb-2">
                                Descrição Curta
                            </label>
                            <textarea name="descricao" 
                                      id="descricao" 
                                      rows="3" 
                                      class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none @error('descricao') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                      placeholder="Breve descrição do que o curso oferece"
                                      maxlength="500">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Detalhes Completos --}}
                        <div>
                            <label for="detalhes" class="block text-sm font-medium text-gray-900 mb-2">
                                Detalhes Completos
                            </label>
                            <textarea name="detalhes" 
                                      id="detalhes" 
                                      rows="5" 
                                      class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none @error('detalhes') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                      placeholder="Descrição detalhada, objetivos, metodologia e conteúdo programático">{{ old('detalhes') }}</textarea>
                            @error('detalhes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ícone SVG --}}
                        <div>
                            <label for="icone_svg" class="block text-sm font-medium text-gray-900 mb-2">
                                Ícone do Curso (SVG)
                            </label>
                            <textarea name="icone_svg" 
                                      id="icone_svg" 
                                      rows="4" 
                                      class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 font-mono text-sm resize-none @error('icone_svg') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                      placeholder="<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 24 24&quot;>...</svg>">{{ old('icone_svg') }}</textarea>
                            @error('icone_svg')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Seção: Auxílios Financeiros --}}
                <div class="p-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        Auxílios Financeiros
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="valor_bolsa_auxilio" class="block text-sm font-medium text-gray-900 mb-2">
                                Bolsa-Auxílio
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">R$</span>
                                </div>
                                <input type="number" 
                                       step="0.01" 
                                       min="0"
                                       name="valor_bolsa_auxilio" 
                                       id="valor_bolsa_auxilio" 
                                       class="block w-full pl-10 pr-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('valor_bolsa_auxilio') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       value="{{ old('valor_bolsa_auxilio') }}"
                                       placeholder="0,00">
                            </div>
                            @error('valor_bolsa_auxilio')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="valor_auxilio_transporte" class="block text-sm font-medium text-gray-900 mb-2">
                                Auxílio Transporte
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">R$</span>
                                </div>
                                <input type="number" 
                                       step="0.01" 
                                       min="0"
                                       name="valor_auxilio_transporte" 
                                       id="valor_auxilio_transporte" 
                                       class="block w-full pl-10 pr-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('valor_auxilio_transporte') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       value="{{ old('valor_auxilio_transporte') }}"
                                       placeholder="0,00">
                            </div>
                            @error('valor_auxilio_transporte')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Seção: Requisitos e Benefícios --}}
                <div class="p-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Requisitos e Benefícios
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label for="requisitos" class="block text-sm font-medium text-gray-900 mb-2">
                                Requisitos
                            </label>
                            <textarea name="requisitos" 
                                      id="requisitos" 
                                      rows="4" 
                                      class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none @error('requisitos') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                      placeholder="Escolaridade mínima, faixa etária, experiência prévia, etc.">{{ old('requisitos') }}</textarea>
                            @error('requisitos')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="beneficios" class="block text-sm font-medium text-gray-900 mb-2">
                                Benefícios Adicionais
                            </label>
                            <textarea name="beneficios" 
                                      id="beneficios" 
                                      rows="4" 
                                      class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none @error('beneficios') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                      placeholder="Certificado de conclusão, material didático, lanche, etc.">{{ old('beneficios') }}</textarea>
                            @error('beneficios')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Seção: Informações Complementares --}}
                <div class="p-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Informações Complementares
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="carga_horaria" class="block text-sm font-medium text-gray-900 mb-2">
                                Carga Horária
                            </label>
                            <input type="text" 
                                   name="carga_horaria" 
                                   id="carga_horaria" 
                                   class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('carga_horaria') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   value="{{ old('carga_horaria') }}"
                                   placeholder="Ex: 40h semanais"
                                   maxlength="50">
                            @error('carga_horaria')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="local_estagio" class="block text-sm font-medium text-gray-900 mb-2">
                                Local do Estágio
                            </label>
                            <input type="text" 
                                   name="local_estagio" 
                                   id="local_estagio" 
                                   class="block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('local_estagio') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   value="{{ old('local_estagio') }}"
                                   placeholder="Ex: Prefeitura Municipal"
                                   maxlength="255">
                            @error('local_estagio')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.cursos.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Salvar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection