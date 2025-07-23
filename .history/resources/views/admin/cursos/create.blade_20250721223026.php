@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-shadow duration-300 hover:shadow-2xl">

        {{-- Cabeçalho --}}
        <header class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white tracking-tight">Cadastrar Novo Curso</h1>
            <a href="{{ route('admin.cursos.index') }}"
               class="p-1 rounded-full text-white hover:bg-white/20 transition"
               title="Fechar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </header>

        {{-- Formulário --}}
        <form action="{{ route('admin.cursos.store') }}" method="POST" class="p-6 md:p-8 space-y-8">
            @csrf

            {{-- Nome do Curso --}}
            <div>
                <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nome do Curso <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nome" id="nome" maxlength="255" required
                       value="{{ old('nome') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nome') border-red-500 @enderror">
                <div class="text-right text-xs text-gray-500 mt-1">
                    <span id="nome-count">0</span>/255
                </div>
                @error('nome')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Descrição Curta --}}
            <div>
                <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-2">
                    Descrição Curta
                </label>
                <textarea name="descricao" id="descricao" rows="3" maxlength="500"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
                          placeholder="Breve descrição do curso">{{ old('descricao') }}</textarea>
                <div class="text-right text-xs text-gray-500 mt-1">
                    <span id="descricao-count">0</span>/500
                </div>
                @error('descricao')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Detalhes Completos --}}
            <div>
                <label for="detalhes" class="block text-sm font-semibold text-gray-700 mb-2">
                    Detalhes Completos
                </label>
                <textarea name="detalhes" id="detalhes" rows="5"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('detalhes') border-red-500 @enderror"
                          placeholder="Metodologia, objetivos, conteúdo programático…">{{ old('detalhes') }}</textarea>
                @error('detalhes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Ícone SVG com Preview --}}
            <div>
                <label for="icone_svg" class="block text-sm font-semibold text-gray-700 mb-2">
                    Código SVG do Ícone
                </label>
                <textarea name="icone_svg" id="icone_svg" rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('icone_svg') border-red-500 @enderror"
                          placeholder="Cole o SVG completo aqui">{{ old('icone_svg') }}</textarea>
                <div id="svg-preview" class="mt-3 flex items-center justify-center h-16 w-16 border border-dashed rounded-lg text-gray-400">
                    Preview
                </div>
                @error('icone_svg')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Valores --}}
            <section class="bg-gradient-to-br from-green-50 to-emerald-50 p-5 rounded-xl border border-green-200">
                <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1" />
                    </svg>
                    Auxílios Financeiros
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Bolsa-Auxílio (R$)</label>
                        <input type="number" step="0.01" min="0" name="valor_bolsa_auxilio"
                               value="{{ old('valor_bolsa_auxilio') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('valor_bolsa_auxilio') border-red-500 @enderror"
                               placeholder="0,00">
                        @error('valor_bolsa_auxilio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Auxílio Transporte (R$)</label>
                        <input type="number" step="0.01" min="0" name="valor_auxilio_transporte"
                               value="{{ old('valor_auxilio_transporte') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('valor_auxilio_transporte') border-red-500 @enderror"
                               placeholder="0,00">
                        @error('valor_auxilio_transporte')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            {{-- Requisitos / Benefícios --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Requisitos</label>
                    <textarea name="requisitos" rows="4"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('requisitos') border-red-500 @enderror"
                              placeholder="Escolaridade, idade, experiência…">{{ old('requisitos') }}</textarea>
                    @error('requisitos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Benefícios Adicionais</label>
                    <textarea name="beneficios" rows="4"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('beneficios') border-red-500 @enderror"
                              placeholder="Certificado, material, alimentação…">{{ old('beneficios') }}</textarea>
                    @error('beneficios')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Informações Complementares --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Carga Horária</label>
                    <input type="text" name="carga_horaria" maxlength="50"
                           value="{{ old('carga_horaria') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('carga_horaria') border-red-500 @enderror"
                           placeholder="Ex: 40h semanais">
                    @error('carga_horaria')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Local do Estágio</label>
                    <input type="text" name="local_estagio" maxlength="255"
                           value="{{ old('local_estagio') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('local_estagio') border-red-500 @enderror"
                           placeholder="Ex: Prefeitura Municipal">
                    @error('local_estagio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Ações --}}
            <footer class="flex justify-end items-center gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.cursos.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Salvar Curso
                </button>
            </footer>
        </form>
    </div>
</div>

@push('scripts')
<script>
/* Contadores de caracteres */
['nome', 'descricao'].forEach(id => {
    const el = document.getElementById(id);
    const counter = document.getElementById(id + '-count');
    el.addEventListener('input', () => counter.textContent = el.value.length);
    el.dispatchEvent(new Event('input')); // inicializa
});

/* Preview do SVG */
const iconeSvg = document.getElementById('icone_svg');
const preview = document.getElementById('svg-preview');
iconeSvg.addEventListener('input', () => {
    preview.innerHTML = iconeSvg.value.trim() || '<span class="text-xs text-gray-400">Preview</span>';
});
iconeSvg.dispatchEvent(new Event('input'));
</script>
@endpush
@endsection