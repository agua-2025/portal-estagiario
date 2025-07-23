@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <div class="bg-white border border-neutral-200">

        {{-- Cabeçalho --}}
        <header class="flex items-center justify-between py-5 px-8 border-b border-neutral-200">
            <h1 class="text-neutral-900 text-xl font-semibold tracking-tight">Cadastrar curso</h1>
            <a href="{{ route('admin.cursos.index') }}" class="p-1 text-neutral-600 hover:text-neutral-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </header>

        {{-- Formulário --}}
        <form action="{{ route('admin.cursos.store') }}" method="POST" class="px-8 py-6 space-y-8">
            @csrf

            {{-- Nome --}}
            <div>
                <label for="nome" class="block text-sm font-medium text-neutral-800">Nome do curso <span class="text-red-600">*</span></label>
                <input type="text" name="nome" id="nome" maxlength="255" required value="{{ old('nome') }}"
                       class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">
                @error('nome') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Descrição curta --}}
            <div>
                <label for="descricao" class="block text-sm font-medium text-neutral-800">Descrição curta</label>
                <textarea name="descricao" id="descricao" rows="3" maxlength="500" class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">{{ old('descricao') }}</textarea>
                @error('descricao') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Detalhes completos --}}
            <div>
                <label for="detalhes" class="block text-sm font-medium text-neutral-800">Detalhes completos</label>
                <textarea name="detalhes" id="detalhes" rows="5" class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">{{ old('detalhes') }}</textarea>
                @error('detalhes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Ícone SVG --}}
            <div>
                <label for="icone_svg" class="block text-sm font-medium text-neutral-800">Código SVG do ícone</label>
                <textarea name="icone_svg" id="icone_svg" rows="4" class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 font-mono text-xs focus:outline-none focus:border-neutral-700">{{ old('icone_svg') }}</textarea>
                @error('icone_svg') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Valores --}}
            <fieldset class="border-t border-neutral-200 pt-6">
                <legend class="text-sm font-medium text-neutral-800">Auxílios financeiros</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="valor_bolsa_auxilio" class="block text-xs font-medium text-neutral-700">Bolsa-auxílio (R$)</label>
                        <input type="number" step="0.01" min="0" name="valor_bolsa_auxilio" id="valor_bolsa_auxilio" value="{{ old('valor_bolsa_auxilio') }}"
                               class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">
                        @error('valor_bolsa_auxilio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="valor_auxilio_transporte" class="block text-xs font-medium text-neutral-700">Auxílio transporte (R$)</label>
                        <input type="number" step="0.01" min="0" name="valor_auxilio_transporte" id="valor_auxilio_transporte" value="{{ old('valor_auxilio_transporte') }}"
                               class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">
                        @error('valor_auxilio_transporte') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Requisitos & Benefícios --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="requisitos" class="block text-sm font-medium text-neutral-800">Requisitos</label>
                    <textarea name="requisitos" id="requisitos" rows="4" class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">{{ old('requisitos') }}</textarea>
                    @error('requisitos') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="beneficios" class="block text-sm font-medium text-neutral-800">Benefícios adicionais</label>
                    <textarea name="beneficios" id="beneficios" rows="4" class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">{{ old('beneficios') }}</textarea>
                    @error('beneficios') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Informações complementares --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="carga_horaria" class="block text-sm font-medium text-neutral-800">Carga horária</label>
                    <input type="text" name="carga_horaria" id="carga_horaria" maxlength="50" value="{{ old('carga_horaria') }}"
                           class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">
                    @error('carga_horaria') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="local_estagio" class="block text-sm font-medium text-neutral-800">Local do estágio</label>
                    <input type="text" name="local_estagio" id="local_estagio" maxlength="255" value="{{ old('local_estagio') }}"
                           class="mt-1 block w-full border border-neutral-300 rounded-sm px-3 py-2 focus:outline-none focus:border-neutral-700">
                    @error('local_estagio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Ações --}}
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-neutral-200">
                <a href="{{ route('admin.cursos.index') }}"
                   class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-sm hover:bg-neutral-50 focus:outline-none focus:border-neutral-700">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-neutral-800 border border-transparent rounded-sm hover:bg-neutral-900 focus:outline-none focus:border-neutral-950">
                    Salvar curso
                </button>
            </div>
        </form>
    </div>
</div>
@endsection