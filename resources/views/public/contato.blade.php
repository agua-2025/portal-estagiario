{{-- resources/views/public/contato.blade.php --}}
@extends('layouts.site')

@section('title', 'Fale Conosco - Portal do Estagiário')

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">Fale Conosco</h1>
        
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8 md:p-12 mb-10">
            {{-- Mensagem de sucesso --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Sucesso!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Mensagens de erro de validação --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Ops!</strong>
                    <span class="block sm:inline">Por favor, corrija os seguintes erros:</span>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contato.send') }}" method="POST" class="space-y-6">
                @csrf {{-- Proteção CSRF --}}

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Seu Nome</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Seu E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">Assunto</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Mensagem</label>
                    <textarea name="message" id="message" rows="5" required
                              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('message') }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
