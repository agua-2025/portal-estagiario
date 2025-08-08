@extends('layouts.site')

@section('title', 'Classificação Geral de Candidatos - Portal do Estagiário')

@section('content')
    <div class="relative min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div data-aos="fade-up">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Classificação Geral de Candidatos</h1>
                <p class="text-gray-600 mb-8">Portal do Estagiário</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-12 z-10 space-y-12">

            {{-- SEÇÃO DE CONVOCADOS --}}
            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Candidatos Convocados</h3>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nome do Candidato</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Curso</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Data da Convocação</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Data de Nasc.</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($convocados as $candidato)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidato->nome_completo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $candidato->curso->nome ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $candidato->convocado_em ? $candidato->convocado_em->format('d/m/Y') : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->data_nascimento ? $candidato->data_nascimento->format('d/m/Y') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Nenhum candidato convocado até o momento.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO DE CLASSIFICAÇÃO GERAL --}}
            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Classificação Geral</h3>
                
                {{-- Loop para cada CURSO --}}
                @forelse ($homologadosAgrupados as $nomeCurso => $candidatos)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800">{{ $nomeCurso ?: 'Curso não especificado' }}</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-white border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Pos.</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Candidato</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Pontuação</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Data de Nasc.</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    {{-- Loop para cada CANDIDATO DENTRO do curso --}}
                                    @foreach ($candidatos as $index => $candidato)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-800 font-semibold' : 
                                                       ($index === 1 ? 'bg-gray-100 text-gray-800 font-semibold' : 
                                                       ($index === 2 ? 'bg-orange-100 text-orange-800 font-semibold' : 'bg-blue-50 text-blue-700')) }} text-sm">
                                                    {{ $index + 1 }}º
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidato->nome_completo }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    {{ number_format($candidato->pontuacao_final, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->data_nascimento ? $candidato->data_nascimento->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
                        Nenhum candidato homologado aguardando convocação.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection