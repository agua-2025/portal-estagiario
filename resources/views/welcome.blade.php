{{-- resources/views/welcome.blade.php --}}
@extends('layouts.site') {{-- ✅ ESSENCIAL: DIZ QUE ESTA PÁGINA USA O LAYOUT GLOBAL 'site.blade.php' --}}
{{-- Define o título da aba do navegador para esta página --}}
@section('title', 'Portal do Estagiário - O seu futuro profissional começa aqui.')
{{-- ✅ INÍCIO DO CONTEÚDO ESPECÍFICO DESTA PÁGINA (o que será injetado no @yield('content')) --}}
@section('content')
    {{-- Seção Hero - Adicionado overflow-hidden para conter elementos absolutos --}}
    <section class="relative gradient-bg min-h-screen flex items-center justify-center text-white overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        {{-- Adicionado max-w-full e overflow-hidden para conter o conteúdo centralizado --}}
        <div class="relative z-10 max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center max-w-full overflow-hidden">
            <div data-aos="fade-up" data-aos-duration="1000">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-shadow mb-6">
                    <span class="block">O seu futuro profissional</span>
                    <span class="block text-yellow-300">começa aqui.</span>
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl md:text-2xl text-gray-100 leading-relaxed">
                    Conectamos talentos promissores às melhores oportunidades de estágio na Prefeitura de Mirassol D'Oeste. Dê o primeiro passo para uma carreira extraordinária.
                </p>
            </div>
            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-full text-purple-700 bg-white hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl hover-scale">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Iniciar Inscrição
                </a>
                <a href="{{ route('classificacao.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-full text-white border-2 border-white hover:bg-white hover:text-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Ver Classificação
                </a>
            </div>
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="400">
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-300 stats-counter">400+</div>
                    <div class="text-gray-200 mt-2">Estagiários Contratados</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-300 stats-counter">20+</div>
                    <div class="text-gray-200 mt-2">Setores da Prefeitura Atendidos</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-300 stats-counter">10+</div>
                    <div class="text-gray-200 mt-2">Áreas Disponíveis</div>
                </div>
            </div>
        </div>
        {{-- Elementos flutuantes - mantidos, mas devem estar dentro dos limites da section --}}
        <div class="absolute top-20 left-10 floating">
            <div class="w-20 h-20 bg-white/10 rounded-full"></div>
        </div>
        <div class="absolute bottom-20 right-10 floating" style="animation-delay: 1s;">
            <div class="w-16 h-16 bg-white/10 rounded-full"></div>
        </div>
    </section>

    {{-- Seção Sobre - Ajuste na contenção da imagem --}}
    <section id="sobre" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right">
                    <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Sobre Nós</h2>
                    <h3 class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">
                        Conectando talentos ao futuro em Mirassol D'Oeste
                    </h3>
                    <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                        Somos o portal oficial de estágio da Prefeitura de Mirassol D'Oeste. Nossa plataforma foi criada para conectar estudantes universitários a oportunidades de estágio nos diversos departamentos da administração pública municipal. Nossa missão é impulsionar o desenvolvimento profissional de jovens talentos, facilitando seu primeiro passo em uma carreira de impacto em nossa cidade.
                    </p>
                    <div class="mt-8 space-y-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Ranking dinâmico e pontuação transparente</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Atualize seu perfil e melhore sua posição a qualquer momento</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-700">Desenvolvimento profissional e estágios de impacto</span>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-left">
                     {{-- Adicionado overflow-hidden para conter o efeito de brilho absoluto --}}
                    <div class="relative overflow-hidden">
                        {{-- O efeito de brilho absoluto pode forçar largura, então limitamos com w-full --}}
                        <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-25 w-full h-full"></div>
                        {{-- A imagem também recebe max-w-full e h-auto para responsividade --}}
                        <img src="https://images.pexels.com/photos/7129700/pexels-photo-7129700.jpeg" alt="Estudantes" class="relative w-full h-96 object-cover rounded-2xl shadow-2xl max-w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Seção Cursos --}}
    <section id="cursos" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Áreas de Atuação</h2>
                <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Seu Conhecimento Tem Valor Aqui</p>
                <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                    Estamos construindo um banco de talentos com os perfis mais promissores para diversas áreas. Cadastre-se, pontue seu perfil e esteja preparado para as vagas que surgirem nos setores da Prefeitura.
                </p>
            </div>
            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($cursos as $curso)
                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover-scale flex flex-col h-full">
                        <div class="p-8 flex flex-col flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mx-auto mb-6">
                                @if ($curso->icone_svg)
                                    {!! $curso->icone_svg !!}
                                @else
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">{{ $curso->nome }}</h3>
                            <p class="text-gray-600 text-center text-sm mb-4">{{ $curso->descricao ?? 'Detalhes sobre o curso e sua área de atuação.' }}</p>
                            <div class="flex justify-center mt-auto">
                                <a href="{{ route('cursos.show', $curso->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                    Saiba Mais &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-600 text-lg py-10">
                        Nenhuma área de atuação disponível no momento.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

<section id="classificacao" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Transparência</h2>
            <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Acompanhe o Processo</p>
            <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                Acompanhe os candidatos convocados e o ranking atualizado em tempo real.
            </p>
        </div>
        
        <div class="space-y-12">
            {{-- CARD 1: Últimos Convocados --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-2xl font-bold text-gray-900 text-center flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Últimos Convocados
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Candidato</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Curso</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Data da Convocação</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Data de Nasc.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($convocados->take(5) as $candidato)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidato->nome_completo_formatado }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $candidato->curso->nome ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->convocado_em ? $candidato->convocado_em->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->data_nascimento ? $candidato->data_nascimento->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        Nenhum candidato convocado até o momento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

{{-- CARD 2: Top 5 Classificação (SEPARADO!) --}}
<div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
        <h3 class="text-2xl font-bold text-gray-900 text-center flex items-center justify-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Top 5 - Classificação Geral
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-20">Pos.</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Candidato</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Curso</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Pontuação</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Data de Nasc.</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($homologados->take(5) as $index => $candidato)
                    <tr class="hover:bg-gray-50 transition-colors duration-150"> {{-- FALTAVA ISSO --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($index === 0)
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 text-yellow-800 font-bold text-lg">
                                    1º
                                </span>
                            @elseif($index === 1)
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-700 font-bold text-lg">
                                    2º
                                </span>
                            @elseif($index === 2)
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-700 font-bold text-lg">
                                    3º
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-50 text-blue-600 font-bold text-base">
                                    {{ $index + 1 }}º
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $candidato->nome_completo_formatado }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $candidato->curso->nome ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-gradient-to-r from-green-50 to-emerald-50 text-green-800">
                                {{ number_format($candidato->pontuacao_final, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $candidato->data_nascimento ? $candidato->data_nascimento->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Nenhum candidato homologado aguardando convocação.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 text-center bg-gradient-to-r from-gray-50 to-gray-100">
        <a href="{{ route('classificacao.index') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-full text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Ver Classificação Completa
            <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
        </div>
        </div>
        </div>
        </section>
        {{-- Seção Documentos --}}
        <section id="documentos" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Informações</h2>
            <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Editais e Documentos</p>
            <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                Aqui você encontra os documentos importantes, editais de chamamento para estágio e todas as informações para se preparar para as futuras oportunidades.
            </p>
            </div>

            <div class="max-w-4xl mx-auto">
            <div class="space-y-6">
                @forelse($docs as $doc)
                @php
                    // Mapeia cores/ícones por tipo
                    $iconMap = [
                    'edital'        => ['bg' => 'bg-rose-100',   'text' => 'text-rose-600',   'name' => 'document'],
                    'manual'        => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600',   'name' => 'book'],
                    'cronograma'    => ['bg' => 'bg-green-100',  'text' => 'text-green-600',  'name' => 'calendar'],
                    'lei'           => ['bg' => 'bg-amber-100',  'text' => 'text-amber-600',  'name' => 'document'],
                    'decreto'       => ['bg' => 'bg-violet-100', 'text' => 'text-violet-600', 'name' => 'stamp'],
                    'noticias'      => ['bg' => 'bg-sky-100',    'text' => 'text-sky-600',    'name' => 'megaphone'],
                    'notícias'      => ['bg' => 'bg-sky-100',    'text' => 'text-sky-600',    'name' => 'megaphone'],
                    'convocacoes'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'name' => 'bell'],
                    'convocações'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'name' => 'bell'],
                    ];
                    $icon = $iconMap[$doc->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'name' => 'document'];

                    $isNew = $doc->published_at && $doc->published_at->gt(now()->subDays(10));
                @endphp

                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
                    data-aos="fade-up"
                    data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $icon['bg'] }}">
                            @switch($icon['name'])
                                @case('calendar')
                                <svg class="w-6 h-6 {{ $icon['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                @break
                                @case('book')
                                <svg class="w-6 h-6 {{ $icon['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13.5M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13.5C4.168 18.977 5.754 18.5 7.5 18.5s3.332.477 4.5 1.253m0-13.5C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13.5c-1.168-.776-2.754-1.253-4.5-1.253s-3.332.477-4.5 1.253"/>
                                </svg>
                                @break
                                @case('stamp') {{-- Decreto (carimbo) --}}
                                <svg class="w-6 h-6 {{ $icon['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3h6v6H9V3zm-3 9h12v3H6v-3zm-1 6h14v3H5v-3z"/>
                                </svg>
                                @break
                                @case('megaphone') {{-- Notícias --}}
                                <svg class="w-6 h-6 {{ $icon['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.9l6.5-1.6A2 2 0 0120 6.2v7.6a2 2 0 01-2.5 1.9L11 14.1M11 5.9v8.2M11 14.1l-3.95 1.3A2 2 0 015 13.5V6.7A2 2 0 017.05 4.8L11 5.9M15 19a3 3 0 01-6 0"/>
                                </svg>
                                @break
                                @case('bell') {{-- Convocações --}}
                                <svg class="w-6 h-6 {{ $icon['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1"/>
                                </svg>
                                @break
                                @default {{-- Document (edital/lei e fallback) --}}
                                <svg class="w-6 h-6 {{ $icon['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endswitch
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $doc->title }}</h3>
                            <p class="text-sm text-gray-500">
                            Publicado em: {{ optional($doc->published_at)->format('d/m/Y') ?: '—' }}
                            @if($doc->ext) • {{ $doc->ext }} @endif
                            @if($doc->size_human) • {{ $doc->size_human }} @endif
                            </p>
                        </div>
                        </div>

                        <div class="flex items-center space-x-3">
                        @if($isNew)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Novo</span>
                        @endif

                        <a href="{{ route('public-docs.download', $doc) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-4-4m4 4l4-4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Baixar
                        </a>
                        </div>
                    </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl p-10 text-center shadow">
                    <p class="text-gray-700 font-medium">Nenhum documento disponível no momento.</p>
                    <p class="text-gray-500 text-sm mt-1">Volte mais tarde 🙂</p>
                </div>
                @endforelse
            </div>
            </div>
        </div>
        </section>

        {{-- Seção Como Funciona --}}
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Sua Jornada Dinâmica</h2>
                    <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Entenda o Processo</p>
                    <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                        Descubra os passos para ativar seu perfil em nosso banco de talentos, entender sua pontuação e acompanhar sua evolução rumo ao estágio ideal.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full text-white">
                            <span class="text-2xl font-bold">1</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Cadastre-se e Pontue</h3>
                        <p class="text-gray-600">Preencha seu perfil com suas informações acadêmicas, cursos e experiências. Cada detalhe conta para sua pontuação inicial!</p>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full text-white">
                            <span class="text-2xl font-bold">2</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Análise e Otimize</h3>
                        <p class="text-gray-600">Adicione novas habilidades, cursos ou projetos. As atualizações serão validadas pela nossa comissão e ajustarão sua pontuação no ranking.
    </p></p>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-green-500 to-green-600 rounded-full text-white">
                            <span class="text-2xl font-bold">3</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Conquiste seu Estágio</h3>
                        <p class="text-gray-600">Quando vagas surgirem, os melhores pontuados serão chamados para contrato. Seu perfil é seu passaporte!</p>
                    </div>
                </div>
            </div>
        </section>

{{-- Seção FAQ --}}
<section id="faq" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Tire Suas Dúvidas</h2>
            <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Perguntas Frequentes</p>
            <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                Encontre respostas rápidas sobre o funcionamento do nosso banco de talentos e o sistema de pontuação.
            </p>
        </div>
        <div class="max-w-4xl mx-auto space-y-6">
            {{-- Pergunta 1 --}}
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8" data-aos="fade-up" data-aos-delay="100">
                <details class="group">
                    <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer text-lg">
                        Como minha pontuação é calculada e o que gera pontos?
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Sua pontuação é baseada nas informações que você cadastra em seu perfil. Ela é calculada considerando seu aproveitamento acadêmico, experiência profissional na área, participação em atividades extracurriculares, cursos de capacitação e o número de semestres que você já cursou. Quanto mais completo e alinhado aos critérios for seu perfil, maior sua pontuação potencial.
                    </p>
                </details>
            </div>

            {{-- Pergunta 2 --}}
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8" data-aos="fade-up" data-aos-delay="200">
                <details class="group">
                    <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer text-lg">
                        Posso atualizar meu perfil a qualquer momento? Isso afeta minha pontuação?
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Sim! Nosso banco de talentos é dinâmico. Você pode acessar seu painel a qualquer momento para adicionar novas experiências, cursos ou certificações. Cada atualização relevante pode aumentar sua pontuação após a validação da comissão, melhorando suas chances no ranking. É fundamental manter seu perfil sempre atualizado para refletir seu desenvolvimento e potencial.
                    </p>
                </details>
            </div>

            {{-- Pergunta 3 (Inalterada) --}}
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8" data-aos="fade-up" data-aos-delay="300">
                <details class="group">
                    <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer text-lg">
                        Quando minhas atualizações e pontuação serão validadas?
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Após você submeter suas atualizações, nossa comissão fará a análise e validação das novas informações. Assim que forem homologadas, sua pontuação e posição na classificação serão ajustadas. O tempo de validação pode variar, mas nos esforçamos para que seja o mais rápido possível.
                    </p>
                </details>
            </div>

            {{-- Pergunta 4 (Inalterada) --}}
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8" data-aos="fade-up" data-aos-delay="400">
                <details class="group">
                    <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer text-lg">
                        Como serei avisado sobre novas vagas de estágio?
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Quando surgirem novas oportunidades, a Prefeitura de Mirassol D'Oeste usará o banco de talentos para identificar perfis compatíveis e com as maiores pontuações. Entraremos em contato com os selecionados por e-mail ou telefone para a contratação.
                    </p>
                </details>
            </div>

            {{-- Pergunta 5 (Inalterada) --}}
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-8" data-aos="fade-up" data-aos-delay="500">
                <details class="group">
                    <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer text-lg">
                        Quais documentos preciso ter para a inscrição?
                        <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        No momento da inscrição inicial, você precisará fornecer suas informações pessoais e acadêmicas. Para a validação e, futuramente, a contratação, documentos como comprovante de matrícula, histórico escolar e documentos de identificação serão solicitados. Consulte os editais para a lista completa.
                    </p>
                </details>
            </div>
        </div>
    </div>
</section>

        {{-- Seção Call to Action --}}
        <section class="py-24 gradient-bg text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div data-aos="fade-up">
                    <h2 class="text-4xl font-bold mb-6">Pronto para subir no ranking e conquistar seu estágio?</h2>
                    <p class="text-xl mb-8 text-gray-100">Junte-se a centenas de estudantes que já pontuam pelas melhores oportunidades na Prefeitura.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-full text-purple-700 bg-white hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl hover-scale">
                        Inscreva-se Agora
                        <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- Script para o contador de estatísticas - Mantido aqui pois é específico da home --}}
        <script>
            function animateNumbers() {
                document.querySelectorAll('.stats-counter').forEach(counter => {
                    const target = parseInt(counter.textContent.replace('+', ''));
                    let current = 0;
                    const increment = target / 100;
                    const updateCounter = () => {
                        if (current < target) {
                            current += increment;
                            counter.textContent = Math.ceil(current) + '+';
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target + '+';
                        }
                    };
                    updateCounter();
                });
            }
            // Ativa a animação quando a seção de estatísticas se torna visível
            const statsSection = document.querySelector('.stats-counter').closest('.grid');
            if (statsSection) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateNumbers();
                            observer.disconnect();
                        }
                    });
                }, { threshold: 0.5 });
                observer.observe(statsSection);
            }
        </script>
    @endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}