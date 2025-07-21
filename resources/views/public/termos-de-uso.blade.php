{{-- resources/views/public/termos-de-uso.blade.php --}}
@extends('layouts.site')

@section('title', $page->title . ' - Portal do Estagiário')

@section('content')
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">{{ $page->title }}</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-8 md:p-12 mb-10 prose max-w-none">
            {!! $page->content !!}
            {{-- Exibição da data de última atualização da página --}}
            <p class="text-gray-500 text-sm italic text-center mt-10">
                Última atualização: {{ $page->updated_at->format('d/m/Y') }}
            </p>
        </div>
    </div>
@endsection
