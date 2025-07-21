{{-- resources/views/public/sobre-nos.blade.php --}}
@extends('layouts.site')

@section('title', $page->title . ' - Portal do Estagiário')

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">{{ $page->title }}</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-8 md:p-12 mb-10 prose max-w-none">
            {!! $page->content !!} {{-- Exibe o conteúdo HTML da página --}}
        </div>
    </div>
@endsection