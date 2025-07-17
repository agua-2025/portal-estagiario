{{-- resources/views/cursos/show.blade.php --}}

@extends('layouts.site') {{-- ESSENCIAL: ESTENDE O LAYOUT GLOBAL 'site.blade.php' --}}

{{-- Define o título da aba do navegador para esta página --}}
@section('title', $curso->nome . ' - Detalhes do Estágio - Portal do Estagiário') {{-- Título ajustado --}}

{{-- ✅ INÍCIO DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}
@section('content')
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2"> {{-- ✅ Fonte reduzida: text-3xl para text-2xl --}}
                    {{ $curso->nome }}
                </h1>
                <p class="text-gray-600 text-base text-justify mx-auto max-w-2xl"> {{-- ✅ Fonte reduzida: text-lg para text-base | Adicionado text-justify --}}
                    {{ $curso->descricao ?? 'Detalhes sobre o curso e sua área de atuação.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"> {{-- ✅ Ícone reduzido: w-6 h-6 para w-5 h-5 --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 011-1h2a2 2 011 1v2a2 2 011 1h-2a2 2 011 1zm0 0l4 4m0 0l-4 4"/>
                        </svg>
                        <span class="text-blue-800 font-semibold text-sm">Bolsa-Auxílio:</span> {{-- ✅ Fonte reduzida: text-sm mantido, já era pequeno --}}
                    </div>
                    <span class="text-blue-800 font-bold text-base">R$ {{ number_format($curso->valor_bolsa_auxilio, 2, ',', '.') ?? 'N/A' }}</span> {{-- ✅ Fonte reduzida: text-lg para text-base --}}
                </div>

                <div class="bg-green-50 p-4 rounded-lg flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"> {{-- ✅ Ícone reduzido: w-6 h-6 para w-5 h-5 --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18"/>
                        </svg>
                        <span class="text-green-800 font-semibold text-sm">Auxílio Transporte:</span>
                    </div>
                    <span class="text-green-800 font-bold text-base">R$ {{ number_format($curso->valor_auxilio_transporte, 2, ',', '.') ?? 'N/A' }}</span> {{-- ✅ Fonte reduzida: text-lg para text-base --}}
                </div>
            </div>

            <div class="mb-8 p-6 bg-gray-50 rounded-lg shadow-inner">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Detalhes do Estágio</h2> {{-- ✅ Fonte reduzida: text-xl para text-lg --}}
                <p class="text-gray-700 text-sm leading-relaxed text-justify"> {{-- ✅ Fonte reduzida: text-base (padrão) para text-sm | Adicionado text-justify --}}
                    {{ $curso->detalhes ?? 'Nenhum detalhe adicional informado para este curso/estágio.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="p-6 bg-gray-50 rounded-lg shadow-inner">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Requisitos</h2> {{-- ✅ Fonte reduzida: text-xl para text-lg --}}
                    <ul class="list-disc list-inside text-gray-700 text-sm space-y-1 text-justify"> {{-- ✅ Fonte reduzida: text-base (padrão) para text-sm | Adicionado text-justify --}}
                        @forelse(explode(';', $curso->requisitos ?? '') as $requisito)
                            @if (!empty(trim($requisito)))
                                <li>{{ trim($requisito) }}</li>
                            @endif
                        @empty
                            <li>Nenhum requisito específico informado.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="p-6 bg-gray-50 rounded-lg shadow-inner">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Benefícios</h2> {{-- ✅ Fonte reduzida: text-xl para text-lg --}}
                    <ul class="list-disc list-inside text-gray-700 text-sm space-y-1 text-justify"> {{-- ✅ Fonte reduzida: text-base (padrão) para text-sm | Adicionado text-justify --}}
                        @forelse(explode(';', $curso->beneficios ?? '') as $beneficio)
                            @if (!empty(trim($beneficio)))
                                <li>{{ trim($beneficio) }}</li>
                            @endif
                        @empty
                            <li>Nenhum benefício específico informado.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="p-6 bg-gray-50 rounded-lg shadow-inner">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Carga Horária</h2> {{-- ✅ Fonte reduzida: text-xl para text-lg --}}
                    <p class="text-gray-700 text-sm text-justify"> {{-- ✅ Fonte reduzida: text-base (padrão) para text-sm | Adicionado text-justify --}}
                        {{ $curso->carga_horaria ?? 'N/A' }}
                    </p>
                </div>

                <div class="p-6 bg-gray-50 rounded-lg shadow-inner">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Local do Estágio</h2> {{-- ✅ Fonte reduzida: text-xl para text-lg --}}
                    <p class="text-gray-700 text-sm text-justify"> {{-- ✅ Fonte reduzida: text-base (padrão) para text-sm | Adicionado text-justify --}}
                        {{ $curso->local_estagio ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    Voltar para a Página Inicial
                </a>
            </div>
        </div>
    </section>
@endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}