{{-- resources/views/public/politica-privacidade.blade.php --}}
@extends('layouts.site') {{-- Esta linha indica que esta página usa o layout principal 'layouts/site.blade.php' --}}

@section('title', $page->title . ' - Portal do Estagiário') {{-- Define o título da aba do navegador dinamicamente --}}

@section('content') {{-- Início da seção de conteúdo desta página --}}
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">{{ $page->title }}</h1>
        
        {{-- Esta div exibe o conteúdo HTML da página que vem do banco de dados --}}
        <div class="bg-white shadow-lg rounded-lg p-8 md:p-12 mb-10 prose max-w-none">
            {!! $page->content !!} {{-- O '{!! !!}' renderiza o HTML sem escapar as tags --}}
        </div>
    </div>
@endsection {{-- Fim da seção de conteúdo --}}
