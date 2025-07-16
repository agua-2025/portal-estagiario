@extends('layouts.admin') {{-- ESTENDE O LAYOUT DO ADMIN --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Removido o h1 daqui --}}

    <div class="bg-white p-8 rounded-lg shadow-md"> {{-- Aumentado padding e shadow para corresponder ao padrão --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Cadastrar Novo Curso</h1> {{-- Movido o h1 para dentro do div do formulário --}}
        <form action="{{ route('admin.cursos.store') }}" method="POST">
            @csrf

            <div class="mb-6"> {{-- Aumentado margin-bottom para espaçamento --}}
                <label for="nome" class="block text-gray-700 text-sm font-semibold mb-2">Nome do Curso:</label> {{-- Font-semibold para labels --}}
                <input type="text" name="nome" id="nome" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('nome') border-red-500 @enderror" value="{{ old('nome') }}" required>
                @error('nome')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> {{-- Ajustado margin-top --}}
                @enderror
            </div>

            <div class="mb-6">
                <label for="instituicao_id" class="block text-gray-700 text-sm font-semibold mb-2">Instituição:</label>
                <select name="instituicao_id" id="instituicao_id" class="form-select w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('instituicao_id') border-red-500 @enderror" required>
                    <option value="">Selecione uma instituição</option>
                    @foreach ($instituicoes as $instituicao)
                        <option value="{{ $instituicao->id }}" {{ old('instituicao_id') == $instituicao->id ? 'selected' : '' }}>
                            {{ $instituicao->nome }}
                        </option>
                    @endforeach
                </select>
                @error('instituicao_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="descricao" class="block text-gray-700 text-sm font-semibold mb-2">Descrição Curta:</label>
                <textarea name="descricao" id="descricao" rows="3" class="form-textarea w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('descricao') border-red-500 @enderror">{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="detalhes" class="block text-gray-700 text-sm font-semibold mb-2">Detalhes Completos do Curso:</label>
                <textarea name="detalhes" id="detalhes" rows="6" class="form-textarea w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('detalhes') border-red-500 @enderror">{{ old('detalhes') }}</textarea>
                @error('detalhes')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"> {{-- Aumentado gap e margin-bottom --}}
                <div>
                    <label for="valor_bolsa_auxilio" class="block text-gray-700 text-sm font-semibold mb-2">Valor da Bolsa-Auxílio (R$):</label>
                    <input type="number" step="0.01" name="valor_bolsa_auxilio" id="valor_bolsa_auxilio" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('valor_bolsa_auxilio') border-red-500 @enderror" value="{{ old('valor_bolsa_auxilio') }}">
                    @error('valor_bolsa_auxilio')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="valor_auxilio_transporte" class="block text-gray-700 text-sm font-semibold mb-2">Valor do Auxílio Transporte (R$):</label>
                    <input type="number" step="0.01" name="valor_auxilio_transporte" id="valor_auxilio_transporte" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('valor_auxilio_transporte') border-red-500 @enderror" value="{{ old('valor_auxilio_transporte') }}">
                    @error('valor_auxilio_transporte')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="requisitos" class="block text-gray-700 text-sm font-semibold mb-2">Requisitos:</label>
                <textarea name="requisitos" id="requisitos" rows="4" class="form-textarea w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('requisitos') border-red-500 @enderror">{{ old('requisitos') }}</textarea>
                @error('requisitos')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="beneficios" class="block text-gray-700 text-sm font-semibold mb-2">Benefícios Adicionais:</label>
                <textarea name="beneficios" id="beneficios" rows="4" class="form-textarea w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('beneficios') border-red-500 @enderror">{{ old('beneficios') }}</textarea>
                @error('beneficios')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"> {{-- Aumentado gap e margin-bottom --}}
                <div>
                    <label for="carga_horaria" class="block text-gray-700 text-sm font-semibold mb-2">Carga Horária:</label>
                    <input type="text" name="carga_horaria" id="carga_horaria" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('carga_horaria') border-red-500 @enderror" value="{{ old('carga_horaria') }}">
                    @error('carga_horaria')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="local_estagio" class="block text-gray-700 text-sm font-semibold mb-2">Local do Estágio:</label>
                    <input type="text" name="local_estagio" id="local_estagio" class="form-input w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('local_estagio') border-red-500 @enderror" value="{{ old('local_estagio') }}">
                    @error('local_estagio')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4"> {{-- Botões alinhados à direita --}}
                <a href="{{ route('admin.cursos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"> {{-- Estilo de botão "Cancelar" --}}
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out shadow-md"> {{-- Estilo de botão "Salvar" --}}
                    Salvar Curso
                </button>
            </div>
        </form>
    </div>
</div>
@endsection