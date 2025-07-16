<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal do Estagiário</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .blob {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            filter: blur(40px);
            animation: blob 7s infinite;
        }
        
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        
        .stats-counter {
            animation: countUp 2s ease-out;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="antialiased bg-gray-50 overflow-x-hidden">
    <!-- Background Blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="blob absolute top-0 left-0 w-72 h-72 opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob absolute top-1/2 right-0 w-96 h-96 opacity-15 translate-x-1/3 -translate-y-1/2" style="animation-delay: 2s;"></div>
        <div class="blob absolute bottom-0 left-1/3 w-80 h-80 opacity-10 translate-y-1/2" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative min-h-screen z-10">
        <!-- CABEÇALHO -->
        <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <!-- LOGO -->
                    <div class="flex-shrink-0" data-aos="fade-right">
                        <a href="/" class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                Portal do Estagiário
                            </span>
                        </a>
                    </div>
                    
                    <!-- NAVEGAÇÃO -->
                    <nav class="hidden md:flex space-x-8" data-aos="fade-down">
                        <a href="#cursos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Áreas de Estágio Disponíveis</a>
                        <a href="#documentos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Editais e Documentos</a>
                        <a href="{{ route('classificacao.index') }}" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Classificação</a>
                        <a href="#sobre" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Sobre</a>
                    </nav>

                    <!-- BOTÕES DE AÇÃO -->
                    <div class="flex items-center space-x-4" data-aos="fade-left">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">Painel</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Entrar</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        Inscreva-se
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- SEÇÃO PRINCIPAL (HERO) -->
            <section class="relative gradient-bg min-h-screen flex items-center justify-center text-white overflow-hidden">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10 max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-shadow mb-6">
                            <span class="block">O seu futuro profissional</span>
                            <span class="block text-yellow-300">começa aqui.</span>
                        </h1>
                        <p class="mt-6 max-w-3xl mx-auto text-xl md:text-2xl text-gray-100 leading-relaxed">
                            Conectamos talentos promissores às melhores oportunidades de estágio. Dê o primeiro passo para uma carreira extraordinária.
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

                    <!-- ESTATÍSTICAS -->
                    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="400">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-yellow-300 stats-counter">400+</div>
                            <div class="text-gray-200 mt-2">Estagiários Contratados</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-yellow-300 stats-counter">30+</div>
                            <div class="text-gray-200 mt-2">Setores da Prefeitura Atendidos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-yellow-300 stats-counter">10+</div>
                            <div class="text-gray-200 mt-2">Áreas de Estágio Disponíveis</div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute top-20 left-10 floating">
                    <div class="w-20 h-20 bg-white/10 rounded-full"></div>
                </div>
                <div class="absolute bottom-20 right-10 floating" style="animation-delay: 1s;">
                    <div class="w-16 h-16 bg-white/10 rounded-full"></div>
                </div>
            </section>

            <!-- SEÇÃO SOBRE -->
            <section id="sobre" class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div data-aos="fade-right">
                            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Sobre Nós</h2>
                            <h3 class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">
                                Conectando talentos ao futuro em Mirassol D'Oeste
                            </h3>
                            <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                                Somos o portal oficial de estágio da Prefeitura de Mirassol D'Oeste. Nossa plataforma foi criada para conectar estudantes universitários a oportunidades exclusivas de estágio nos diversos departamentos da administração pública municipal. Nossa missão é impulsionar o desenvolvimento profissional de jovens talentos, facilitando seu primeiro passo em uma carreira de impacto em nossa cidade.
                            </p>
                            <div class="mt-8 space-y-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Processo seletivo transparente</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Desenvolvimento profissional de verdade</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">Estágios nas unidades da Prefeitura Municipal</span>
                                </div>
                            </div>
                        </div>
                        <div data-aos="fade-left">
                            <div class="relative">
                                <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-25"></div>
                                <img src="https://images.pexels.com/photos/1462630/pexels-photo-1462630.jpeg" alt="Estudantes" class="relative w-full h-96 object-cover rounded-2xl shadow-2xl">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- VITRINE DE CURSOS -->
            <section id="cursos" class="py-24 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center" data-aos="fade-up">
                        <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Oportunidades</h2>
                        <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Áreas de Estágio Disponíveis</p> {{-- Título ajustado --}}
                        <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                            Formamos um banco de talentos para diversas áreas do conhecimento. Cadastre-se e esteja pronto para quando a oportunidade surgir.
                        </p> {{-- Descrição ajustada --}}
                    </div>
                    
                    <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <!-- ✅ AQUI É ONDE OS CURSOS SERÃO EXIBIDOS DINAMICAMENTE -->
                        @forelse($cursos as $curso)
                            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover-scale" data-aos="fade-up" data-aos-delay="100">
                                <div class="p-8">
                                    <div class="flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mx-auto mb-6">
                                        {{-- Ícone genérico para cursos. Você pode adicionar lógica para ícones específicos por curso se tiver um campo 'icone' no seu modelo Curso --}}
                                        @if ($curso->icone_svg)
    {!! $curso->icone_svg !!} {{-- ✅ EXIBE O SVG CADASTRADO --}}
@else
    {{-- Ícone genérico de fallback se nenhum SVG for cadastrado --}}
    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>
@endif
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">{{ $curso->nome }}</h3>
                                    {{-- Removida a linha de vagas disponíveis --}}
                                    <p class="text-gray-600 text-center text-sm mb-4 h-10">{{ $curso->descricao ?? 'Detalhes sobre o curso e sua área de atuação.' }}</p> {{-- Supondo que você tenha um campo 'descricao' --}}
                                    <div class="flex justify-center">
                                        {{-- ✅ LINK ATUALIZADO PARA APONTAR PARA A ROTA DE DETALHES DO CURSO --}}
                                        <a href="{{ route('cursos.show', $curso->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                            Saber Mais &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Mensagem se não houver cursos cadastrados --}}
                            <div class="col-span-full text-center text-gray-600 text-lg py-10">
                                Nenhuma área de atuação disponível no momento.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <!-- SEÇÃO DE CLASSIFICAÇÃO -->
            <section id="classificacao" class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16" data-aos="fade-up">
                        <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Transparência</h2>
                        <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Classificação dos Candidatos</p>
                        <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">Acompanhe sua posição em tempo real
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-2xl p-8" data-aos="fade-up" data-aos-delay="200">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Posição</th>
                                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Candidato</th>
                                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Curso</th>
                                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Pontuação</th>
                                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-100 hover:bg-white transition-colors duration-200">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-sm">1</div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">João Silva</div>
                                            <div class="text-sm text-gray-500">joao.silva@email.com</div>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-900">Direito</td>
                                        <td class="py-4 px-6 text-sm text-gray-900">95.8</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Aprovado</span>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100 hover:bg-white transition-colors duration-200">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-bold text-sm">2</div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">Maria Santos</div>
                                            <div class="text-sm text-gray-500">maria.santos@email.com</div>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-900">Engenharia</td>
                                        <td class="py-4 px-6 text-sm text-gray-900">93.2</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Em Análise</span>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100 hover:bg-white transition-colors duration-200">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-orange-400 rounded-full flex items-center justify-center text-white font-bold text-sm">3</div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">Pedro Costa</div>
                                            <div class="text-sm text-gray-500">pedro.costa@email.com</div>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-900">Administração</td>
                                        <td class="py-4 px-6 text-sm text-gray-900">91.7</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Aprovado</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-8 flex justify-center">
                            <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                Ver Classificação Completa
                                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO DE DOCUMENTOS -->
            <section id="documentos" class="py-24 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16" data-aos="fade-up">
                        <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Informações</h2>
                        <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Editais e Documentos</p>
                        <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                            Acesse todos os documentos necessários para sua inscrição e acompanhe as atualizações do processo seletivo.
                        </p>
                    </div>
                    
                    <div class="max-w-4xl mx-auto">
                        <div class="space-y-6">
                            <!-- Documento -->
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                                <div class="p-8">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Edital de Abertura 001/2025</h3>
                                                <p class="text-sm text-gray-500">Publicado em: 10/07/2025 • PDF • 2.5 MB</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Novo</span>
                                            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-2 14h-4a2 2 0 01-2-2V5a2 2 0 012-2h4a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                                                </svg>
                                                Baixar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documento -->
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                                <div class="p-8">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Manual do Candidato</h3>
                                                <p class="text-sm text-gray-500">Publicado em: 05/07/2025 • PDF • 1.8 MB</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-2 14h-4a2 2 0 01-2-2V5a2 2 0 012-2h4a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                                                </svg>
                                                Baixar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documento -->
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                                <div class="p-8">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Cronograma do Processo Seletivo</h3>
                                                <p class="text-sm text-gray-500">Publicado em: 01/07/2025 • PDF • 0.9 MB</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-2 14h-4a2 2 0 01-2-2V5a2 2 0 012-2h4a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                                                </svg>
                                                Baixar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO DE PROCESSO -->
            <section class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16" data-aos="fade-up">
                        <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Como Funciona</h2>
                        <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Processo Seletivo</p>
                        <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                            Conheça as etapas do nosso processo seletivo transparente e justo.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Etapa 1 -->
                        <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full text-white">
                                <span class="text-2xl font-bold">1</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Inscrição</h3>
                            <p class="text-gray-600">Faça sua inscrição online preenchendo o formulário com seus dados acadêmicos e profissionais.</p>
                        </div>
                        
                        <!-- Etapa 2 -->
                        <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full text-white">
                                <span class="text-2xl font-bold">2</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Análise</h3>
                            <p class="text-gray-600">Nossa equipe analisa seu perfil e experiência para verificar a compatibilidade com as vagas disponíveis.</p>
                        </div>
                        
                        <!-- Etapa 3 -->
                        <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-green-500 to-green-600 rounded-full text-white">
                                <span class="text-2xl font-bold">3</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Resultado</h3>
                            <p class="text-gray-600">Acompanhe o resultado em tempo real através da nossa plataforma de classificação transparente.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO DE DEPOIMENTOS -->
            <section class="py-24 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16" data-aos="fade-up">
                        <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Depoimentos</h2>
                        <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">O que nossos estagiários dizem</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Depoimento 1 -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                            <div class="flex items-center mb-6">
                                <img class="w-12 h-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1494790108755-2616b2e31d01?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Ana Silva">
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Ana Silva</h4>
                                    <p class="text-gray-600">Estagiária de Direito</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">"A plataforma me conectou com uma oportunidade incrível. O processo foi transparente e justo. Recomendo para todos os estudantes!"</p>
                        </div>
                        
                        <!-- Depoimento 2 -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                            <div class="flex items-center mb-6">
                                <img class="w-12 h-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Carlos Santos">
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Carlos Santos</h4>
                                    <p class="text-gray-600">Estagiário de Engenharia</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">"Consegui meu primeiro estágio através do portal. A empresa era exatamente o que eu procurava. Muito obrigado pela oportunidade!"</p>
                        </div>
                        
                        <!-- Depoimento 3 -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                            <div class="flex items-center mb-6">
                                <img class="w-12 h-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Mariana Costa">
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Mariana Costa</h4>
                                    <p class="text-gray-600">Estagiária de Marketing</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">"Interface intuitiva e processo rápido. Em poucos dias já estava trabalhando na empresa dos meus sonhos. Recomendo!"</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA FINAL -->
            <section class="py-24 gradient-bg text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div data-aos="fade-up">
                        <h2 class="text-4xl font-bold mb-6">Pronto para começar sua jornada?</h2>
                        <p class="text-xl mb-8 text-gray-100">Junte-se a centenas de estudantes que já encontraram suas oportunidades.</p>
                        <a href="#" class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-full text-purple-700 bg-white hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl hover-scale">
                            Inscreva-se Agora
                            <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
        </main>
        
        <!-- RODAPÉ -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <a href="#" class="flex items-center justify-center md:justify-start space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                Portal do Estagiário
                            </span>
                        </a>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Seu ponto de partida para as melhores oportunidades de estágio. Conectando o agora ao futuro.
                        </p>
                    </div>

                    <div data-aos="fade-up" data-aos-duration="1000">
                        <h4 class="text-lg font-semibold text-white mb-4">Links Úteis</h4>
                        <ul class="space-y-2">
                            <li><a href="#cursos" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Áreas de Estágio Disponíveis</a></li>
                            <li><a href="#documentos" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Editais e Documentos</a></li>
                            <li><a href="#classificacao" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Classificação</a></li>
                            <li><a href="#sobre" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Sobre Nós</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Política de Privacidade</a></li>
                        </ul>
                    </div>

                    <div data-aos="fade-left" data-aos-duration="1000">
                        <h4 class="text-lg font-semibold text-white mb-4">Contato</h4>
                        <p class="text-gray-400 text-sm mb-4">
                            Dúvidas? Entre em contato conosco!
                            <br>
                            E-mail: <a href="mailto:contato@portaldoestagiario.com.br" class="text-blue-400 hover:underline">contato@portaldoestagiario.com.br</a>
                        </p>
                        <h4 class="text-lg font-semibold text-white mb-4">Siga-nos</h4>
                        <div class="flex justify-center md:justify-start space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.0007 2.00067C6.48667 2.00067 2.00067 6.48667 2.00067 12.0007C2.00067 17.5147 6.48667 22.0007 12.0007 22.0007C17.5147 22.0007 22.0007 17.5147 22.0007 12.0007C22.0007 6.48667 17.5147 2.00067 12.0007 2.00067ZM15.0113 5.48533C15.0113 5.99933 14.5953 6.41533 14.0813 6.41533C13.5673 6.41533 13.1513 5.99933 13.1513 5.48533C13.1513 4.97133 13.5673 4.55533 14.0813 4.55533C14.5953 4.55533 15.0113 4.97133 15.0113 5.48533ZM12.0007 7.00067C9.23933 7.00067 7.00067 9.23933 7.00067 12.0007C7.00067 14.762 9.23933 17.0007 12.0007 17.0007C14.762 17.0007 17.0007 14.762 17.0007 12.0007C17.0007 9.23933 14.762 7.00067 12.0007 7.00067ZM12.0007 8.86733C13.7313 8.86733 15.134 10.27 15.134 12.0007C15.134 13.7313 13.7313 15.134 12.0007 15.134C10.27 15.134 8.86733 13.7313 8.86733 12.0007C8.86733 10.27 10.27 8.86733 12.0007 8.86733Z" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2C6.477 2 2 6.477 2 12c0 4.29 2.766 7.935 6.64 9.263.485.09.663-.209.663-.464 0-.228-.009-.834-.014-1.637-2.693.585-3.268-1.295-3.268-1.295-.441-1.121-1.076-1.423-1.076-1.423-.878-.598.066-.586.066-.586.97.069 1.479.998 1.479.998.864 1.477 2.274 1.05 2.825.803.087-.624.339-1.05.617-1.292-2.156-.245-4.428-1.078-4.428-4.795 0-1.058.377-1.928 1.002-2.607-.1-.247-.435-1.232.096-2.569 0 0 .817-.261 2.673.998.779-.217 1.61-.326 2.44-.33-.83.006-1.66.115-2.44.33-.23.69-.691 1.656-.096 2.569.625.68 1.002 1.549 1.002 2.607 0 3.725-2.275 4.547-4.437 4.789.347.3.656.892.656 1.794 0 1.292-.012 2.332-.012 2.648 0 .256.176.558.667.458C19.236 20.063 22 16.418 22 12z" />
                                </svg>
                            </a>
                            <!-- Adicione mais ícones de redes sociais conforme necessário -->
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
                    &copy; 2025 Portal do Estagiário. Todos os direitos reservados.
                </div>
            </div>
        </footer>
    </div>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-back',
        });

        // Script para o contador de estatísticas
        function animateNumbers() {
            document.querySelectorAll('.stats-counter').forEach(counter => {
                const target = parseInt(counter.textContent.replace('+', ''));
                let current = 0;
                const increment = target / 100; // Altere para controlar a velocidade da animação

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
                        observer.disconnect(); // Para de observar após a animação
                    }
                });
            }, { threshold: 0.5 }); // Define quando a animação será acionada (50% visível)
            observer.observe(statsSection);
        }
    </script>
</body>
</html>
