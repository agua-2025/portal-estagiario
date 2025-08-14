<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <!-- Adicionando viewport-fit=cover para melhor controle em dispositivos com entalhe (notch) -->
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?php echo $__env->yieldContent('title', 'Portal do Estagiário'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" href="<?php echo e(asset('favicon.png')); ?>" type="image/png">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* --- CORREÇÕES FINAIS PARA CONTER TOTALMENTE A LARGURA --- */
        /* Reforçando contenção no html e body */
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            width: 100%;
            /* Impedir qualquer elemento filho de forçar largura */
            position: relative;
        }

        /* Reforçando box-sizing */
        *, *::before, *::after {
            max-width: 100%;
            box-sizing: border-box;
            /* Impedir elementos absolutos de escaparem */
            left: 0 !important;
            right: 0 !important;
        }

        /* Contenção absoluta do contêiner principal */
        .relative.min-h-screen.z-10 {
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            position: relative;
        }

        /* Contenção ABSOLUTA do contêiner de fundo e blobs */
        .fixed.inset-0.overflow-hidden.pointer-events-none.z-0 {
            width: 100vw;
            max-width: 100vw;
            overflow: hidden !important; /* Força esconder qualquer overflow */
            left: 0 !important;
            right: 0 !important;
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            /* Isola completamente este elemento e seus filhos */
            contain: strict !important;
        }

        /* Correção FINAL para os blobs */
        .blob {
            position: absolute !important;
            /* Limitar tamanho máximo dos blobs */
            max-width: calc(100vw - 20px) !important;
            max-height: calc(100vh - 20px) !important;
            width: auto !important;
            height: auto !important;
            /* Garantir que o blur não cause overflow */
            filter: blur(40px) !important;
            /* Desativar animações que podem causar overflow em edge cases */
            /* animation: none !important; */ /* Descomente se ainda tiver problema */
        }

        /* Forçar contenção nas classes max-w-7xl do Tailwind */
        .max-w-7xl {
            max-width: min(80rem, 100vw) !important; /* Não ultrapassar 100vw */
            width: 100% !important;
        }

        /* Garantir que o header não force largura */
        header {
            width: 100vw;
            max-width: 100vw;
            left: 0;
            right: 0;
            overflow-x: hidden;
        }

        /* --- SEUS ESTILOS EXISTENTES (mantidos e ajustados) --- */
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
        /* .blob { ... } - Removido daqui, já definido acima */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(10px, -20px) scale(1.05); } /* Movimento reduzido */
            66% { transform: translate(-10px, 10px) scale(0.95); } /* Movimento reduzido */
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .stats-counter {
            animation: countUp 2s ease-out;
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Alpine.js x-cloak para evitar flicker de conteúdo x-show */
        [x-cloak] { display: none !important; }
        /* Cores de status replicadas para consistência (usadas na classificação) */
        .status-approved { background-color: #d4edda; color: #155724; } /* bg-green-100 text-green-800 */
        .status-rejected { background-color: #f8d7da; color: #721c24; } /* bg-red-100 text-red-800 */
        .status-analise { background-color: #fff3cd; color: #856404; } /* bg-yellow-100 text-yellow-800 */
        /* Correção específica para o texto do logo */
        .logo-text-fix {
            overflow: visible !important;
            text-overflow: clip !important;
            white-space: nowrap !important;
            max-width: none !important;
            width: auto !important;
            display: inline-block !important;
        }
        /* Garantir que containers não ultrapassem a tela */
        .container-fix {
            width: 100%;
            max-width: 100vw;
            overflow: hidden;
        }
        /* Forçar elementos flexbox a não ultrapassarem */
        .flex-fix {
            min-width: 0;
            flex-shrink: 1;
        }
        /* Media query para telas muito pequenas - esconder parte do texto se necessário */
        @media (max-width: 320px) {
            .logo-text-fix {
                font-size: 0.75rem !important;
            }
        }
        /* Estilos do menu mobile */
        .mobile-menu {
            transform: translateX(-100%);
        }
        .mobile-menu.open {
            transform: translateX(0);
        }
        /* Melhorias para mobile */
        @media (max-width: 768px) {
            .blob {
                width: 150px !important; /* Reduzido */
                height: 150px !important;
                max-width: calc(100vw - 20px) !important;
            }
            .floating {
                animation: none; /* Remove animação em mobile para performance */
            }
        }
        /* Ajustes para telas pequenas */
        @media (max-width: 640px) {
            .text-2xl { font-size: 1.25rem; }
            .text-xl { font-size: 1.125rem; }
            .text-lg { font-size: 1rem; }
        }
        /* Ajustes específicos para telas muito pequenas */
        @media (max-width: 375px) {
            .text-sm { font-size: 0.75rem; }
        }
    </style>
</head>
<!-- Reforçando overflow-x-hidden no body -->
<body class="antialiased bg-gray-50 overflow-x-hidden">
    <!-- Reforçando contenção no contêiner de fundo -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <!-- Blobs com tamanhos limitados -->
        <div class="blob absolute top-0 left-0 w-48 sm:w-72 h-48 sm:h-72 opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob absolute top-1/2 right-0 w-64 sm:w-96 h-64 sm:h-96 opacity-15 translate-x-1/3 -translate-y-1/2" style="animation-delay: 2s;"></div>
        <div class="blob absolute bottom-0 left-1/3 w-56 sm:w-80 h-56 sm:h-80 opacity-10 translate-y-1/2" style="animation-delay: 4s;"></div>
    </div>
    <!-- Reforçando contenção no contêiner principal -->
    <div class="relative min-h-screen z-10 overflow-x-hidden">
        <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-3 sm:py-4">
                    <!-- Logo e Título -->
                    <div class="flex items-center space-x-2" data-aos="fade-right"> <a href="<?php echo e(route('welcome')); ?>" class="flex items-center space-x-2">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-base sm:text-lg lg:text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    Portal do Estagiário
                                </span>
                            </div>
                        </a>
                    </div>
                    <!-- Menu Desktop -->
                    <nav class="hidden lg:flex space-x-8" data-aos="fade-down">
                        <a href="<?php echo e(route('welcome')); ?>#cursos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Cursos Disponíveis</a>
                        <a href="<?php echo e(route('welcome')); ?>#documentos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Editais e Documentos</a>
                        <a href="<?php echo e(route('classificacao.index')); ?>" class="text-base font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200">Classificação</a>
                        <a href="<?php echo e(route('welcome')); ?>#sobre" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Sobre</a>
                    </nav>
                    <!-- Botões de Auth Desktop -->
                    <div class="hidden sm:flex items-center space-x-2 flex-shrink-0" data-aos="fade-left">
                        <?php if(Route::has('login')): ?>
                            <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(url('/dashboard')); ?>" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">Painel</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Entrar</a>
                                <?php if(Route::has('register')): ?>
                                    <a href="<?php echo e(route('register')); ?>" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl sm:px-6">
                                        Inscreva-se
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <!-- Botão do Menu Mobile -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition-colors duration-200">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Menu Mobile -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 x-cloak
                 class="sm:hidden bg-white border-t border-gray-200">
                <div class="px-4 py-3 space-y-3">
                    <a href="<?php echo e(route('welcome')); ?>#cursos" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200" @click="mobileMenuOpen = false">Cursos Disponíveis</a>
                    <a href="<?php echo e(route('welcome')); ?>#documentos" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200" @click="mobileMenuOpen = false">Editais e Documentos</a>
                    <a href="<?php echo e(route('classificacao.index')); ?>" class="block text-base font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200" @click="mobileMenuOpen = false">Classificação</a>
                    <a href="<?php echo e(route('welcome')); ?>#sobre" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200" @click="mobileMenuOpen = false">Sobre</a>
                    <a href="<?php echo e(route('welcome')); ?>#perguntas-frequentes" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200" @click="mobileMenuOpen = false">Perguntas Frequentes (FAQ)</a>
                    <!-- Auth buttons para mobile -->
                    <div class="pt-3 border-t border-gray-200 space-y-3">
                        <?php if(Route::has('login')): ?>
                            <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(url('/dashboard')); ?>" class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg" @click="mobileMenuOpen = false">Painel</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="block text-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200" @click="mobileMenuOpen = false">Entrar</a>
                                <?php if(Route::has('register')): ?>
                                    <a href="<?php echo e(route('register')); ?>" class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg" @click="mobileMenuOpen = false">
                                        Inscreva-se
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
        <footer class="bg-gray-900 text-white py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 text-center sm:text-left">
                    
                    <div class="sm:col-span-2 lg:col-span-1" data-aos="fade-right" data-aos-duration="1000">
                        <a href="<?php echo e(route('welcome')); ?>" class="flex items-center justify-center sm:justify-start space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-lg sm:text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                Portal do Estagiário
                            </span>
                        </a>
                        <p class="text-gray-400 text-sm leading-relaxed mb-4 max-w-xs mx-auto sm:max-w-none sm:mx-0">
                            Seu ponto de partida para as melhores oportunidades de estágio. Conectando o agora ao futuro.
                        </p>
                        
                        <div class="flex justify-center sm:justify-start space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200" title="Facebook">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200" title="Instagram">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.0007 2.00067C6.48667 2.00067 2.00067 6.48667 2.00067 12.0007C2.00067 17.5147 6.48667 22.0007 12.0007 22.0007C17.5147 22.0007 22.0007 17.5147 22.0007 12.0007C22.0007 6.48667 17.5147 2.00067 12.0007 2.00067ZM15.0113 5.48533C15.0113 5.99933 14.5953 6.41533 14.0813 6.41533C13.5673 6.41533 13.1513 5.99933 13.1513 5.48533C13.1513 4.97133 13.5673 4.55533 14.0813 4.55533C14.5953 4.55533 15.0113 4.97133 15.0113 5.48533ZM12.0007 7.00067C9.23933 7.00067 7.00067 9.23933 7.00067 12.0007C7.00067 14.762 9.23933 17.0007 12.0007 17.0007C14.762 17.0007 17.0007 14.762 17.0007 12.0007C17.0007 9.23933 14.762 7.00067 12.0007 7.00067ZM12.0007 8.86733C13.7313 8.86733 15.134 10.27 15.134 12.0007C15.134 13.7313 13.7313 15.134 12.0007 15.134C10.27 15.134 8.86733 13.7313 8.86733 12.0007C8.86733 10.27 10.27 8.86733 12.0007 8.86733Z" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200" title="GitHub">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2C6.477 2 2 6.477 2 12c0 4.29 2.766 7.935 6.64 9.263.485.09.663-.209.663-.464 0-.228-.009-.834-.014-1.637-2.693.585-3.268-1.295-3.268-1.295-.441-1.121-1.076-1.423-1.076-1.423-.878-.598.066-.586.066-.586.97.069 1.479.998 1.479.998.864 1.477 2.274 1.05 2.825.803.087-.624.339-1.05.617-1.292-2.156-.245-4.428-1.078-4.428-4.795 0-1.058.377-1.928 1.002-2.607-.1-.247-.435-1.232.096-2.569 0 0 .817-.261 2.673.998.779-.217 1.61-.326 2.44-.33-.83.006-1.66.115-2.44.33-.23.69-.691 1.656-.096 2.569.625.68 1.002 1.549 1.002 2.607 0 3.725-2.275 4.547-4.437 4.789.347.3.656.892.656 1.794 0 1.292-.012 2.332-.012 2.648 0 .256.176.558.667.458C19.236 20.063 22 16.418 22 12z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <h4 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">Navegação</h4>
                        <ul class="space-y-2">
                            <li><a href="<?php echo e(route('welcome')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Home</a></li>
                            <li><a href="<?php echo e(route('sobre-nos')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Sobre Nós</a></li>
                            <li><a href="<?php echo e(route('politica-privacidade')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Política de Privacidade</a></li>
                            <li><a href="<?php echo e(route('termos-de-uso')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Termos de Uso</a></li>
                        </ul>
                    </div>
                    
                    <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                        <h4 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">Legislação</h4>
                        <ul class="space-y-2">
                            <li><a href="https://www.planalto.gov.br/ccivil_03/_ato2007-2010/2008/lei/l11788.htm" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm break-words">Lei Federal nº 11.788</a></li>
                            <li><a href="https://leismunicipais.com.br/a1/mt/m/mirassol-do-oeste/lei-ordinaria/2017/141/1409/lei-ordinaria-n-1409-2017-dispoe-sobre-o-estagio-de-estudantes-no-ambito-do-municipio-de-mirassol-d-oeste-mt-em-conformidade-com-o-estabelecido-na-lei-federal-n-11788-2008-e-da-outras-providencias?q=1.409" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm break-words">Lei Municipal nº 1.409</a></li>
                            <li><a href="https://leismunicipais.com.br/a1/mt/m/mirassol-do-oeste/decreto/2023/446/4458/decreto-n-4458-2023-dispoe-sobre-a-criacao-de-banco-de-curriculos-para-estagio-remunerado-de-diversas-areas-do-ensino-superior-e-estabelece-criterios-para-selecao-dos-candidatos?q=4.458" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm break-words">Decreto nº 4.458/23</a></li>
                        </ul>
                    </div>
                    
                    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                        <h4 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">Contato</h4>
                        <ul class="space-y-2">
                            <li class="text-gray-400 text-sm">(65) 99930-6419</li>
                            <li><a href="<?php echo e(route('contato.show')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Fale Conosco</a></li>
                            <li><a href="<?php echo e(route('perguntas-frequentes')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Perguntas Frequentes</a></li>
                        </ul>
                    </div>
                </div>
                 <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-gray-800 text-center text-gray-400 text-xs sm:text-sm">
                    &copy; <?php echo e(date('Y')); ?> Portal do Estagiário. Todos os direitos reservados.
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
</script>
        </footer>
</div>
<script>
    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-out-back',
    });
</script>
        </footer>
</div>
<script>
    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-out-back',
    });
</script>
        </footer>
</div>
<script>
    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-out-back',
    });
</script>
        </footer>
</div>



<div style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <div id="whatsapp-widget" style="
        position: absolute; 
        bottom: 80px; 
        right: 0; 
        background: white; 
        border-radius: 15px; 
        box-shadow: 0 5px 20px rgba(0,0,0,0.3); 
        padding: 20px; 
        width: 300px; 
        display: none;
    ">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <div style="
                width: 40px; 
                height: 40px; 
                background: #25d366; 
                border-radius: 50%; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                margin-right: 10px;
            ">
                <svg style="width: 24px; height: 24px; fill: white;" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.785"/>
                </svg>
            </div>
            <div>
                <div style="font-weight: bold; color: #333;">Suporte Portal</div>
                <div id="status-text" style="font-size: 12px; color: #25d366;">● Online agora</div>
            </div>
        </div>
        
        <div id="message-content">
            <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.4;">
                Olá! 👋 Precisa de ajuda com seu cadastro ou ranking?
            </p>
            <a href="https://wa.me/5565998000683?text=Olá!%20Preciso%20de%20ajuda%20com%20o%20Portal%20do%20Estagiário%20-%20Mirassol%20D'Oeste" 
                target="_blank" 
                style="
                    display: block; 
                    background: #25d366; 
                    color: white; 
                    padding: 10px; 
                    border-radius: 8px; 
                    text-decoration: none; 
                    text-align: center;
                    font-weight: bold;
                ">
                💬 Iniciar Conversa
            </a>
        </div>
    </div>
    
    <button onclick="toggleWidget()" style="
        background: #25d366; 
        color: white; 
        padding: 15px; 
        border-radius: 50%; 
        border: none; 
        cursor: pointer; 
        width: 60px;
        height: 60px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
        <svg style="width: 32px; height: 32px; fill: white;" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.785"/>
        </svg>
        
        <div id="status-badge" style="
            position: absolute; 
            top: -2px; 
            right: -2px; 
            width: 12px; 
            height: 12px; 
            background: #10b981; 
            border-radius: 50%; 
            border: 2px solid white;
        "></div>
    </button>
</div>

<script>
    // Substitua TODO o conteúdo desta tag script pelo código abaixo:
    (function() {
        let isOpen = false;

        // Função para alternar o widget
        function toggleWidget() {
            const widget = document.getElementById('whatsapp-widget');
            if (isOpen) {
                widget.style.display = 'none';
                isOpen = false;
            } else {
                widget.style.display = 'block';
                isOpen = true;
            }
        }

        // Função para verificar horário de atendimento
        function checkBusinessHours() {
            const now = new Date();
            const hour = now.getHours();
            const day = now.getDay(); // 0 = domingo, 1 = segunda, etc.
            
            // Segunda a sexta (1-5) das 7h às 13h
            const isWeekday = day >= 1 && day <= 5;
            const isBusinessHour = hour >= 7 && hour < 13;
            
            return isWeekday && isBusinessHour;
        }

        // Função para atualizar o status
        function updateStatus() {
            const isOnline = checkBusinessHours();
            const statusText = document.getElementById('status-text');
            const statusBadge = document.getElementById('status-badge');
            const messageContent = document.getElementById('message-content');
            
            if (isOnline) {
                // Online
                statusText.innerHTML = '● Online agora';
                statusText.style.color = '#25d366';
                statusBadge.style.background = '#10b981';
                
                messageContent.innerHTML = `
                    <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.4;">
                        Olá! 👋 Estamos online e prontos para ajudar!
                    </p>
                    <a href="https://wa.me/556599306419?text=Olá!%20Preciso%20de%20ajuda%20com%20o%20Portal%20do%20Estagiário%20-%20Mirassol%20D'Oeste" 
                        target="_blank" 
                        style="
                            display: block; 
                            background: #25d366; 
                            color: white; 
                            padding: 10px; 
                            border-radius: 8px; 
                            text-decoration: none; 
                            text-align: center;
                            font-weight: bold;
                        ">
                        💬 Falar Agora
                    </a>
                `;
            } else {
                // Offline
                statusText.innerHTML = '● Offline';
                statusText.style.color = '#999';
                statusBadge.style.background = '#999';
                
                messageContent.innerHTML = `
                    <p style="color: #666; font-size: 13px; margin-bottom: 10px;">
                        <strong>Horário de Atendimento:</strong><br>
                        Segunda à Sexta: 7h às 13h
                    </p>
                    <p style="color: #999; font-size: 12px; margin-bottom: 15px;">
                        Deixe sua mensagem que responderemos em breve!
                    </p>
                    <a href="https://wa.me/5565998000683?text=Olá!%20Estou%20deixando%20uma%20mensagem%20sobre%20o%20Portal%20do%20Estagiário" 
                        target="_blank" 
                        style="
                            display: block; 
                            background: #999; 
                            color: white; 
                            padding: 10px; 
                            border-radius: 8px; 
                            text-decoration: none; 
                            text-align: center;
                            font-weight: bold;
                        ">
                        📝 Deixar Mensagem
                    </a>
                `;
            }
        }

        // Fechar widget ao clicar fora
        document.addEventListener('click', function(e) {
            const widget = document.getElementById('whatsapp-widget');
            const button = document.querySelector('button[onclick="toggleWidget()"]'); // Seleciona o botão específico
            
            // Verifica se o clique não foi dentro do widget e nem no botão que o abre
            if (isOpen && !widget.contains(e.target) && (!button || !button.contains(e.target))) {
                widget.style.display = 'none';
                isOpen = false;
            }
        });


        // Inicializar o status ao carregar a página
        updateStatus();

        // Atualizar status a cada minuto
        setInterval(updateStatus, 60000);

        // Expor toggleWidget para o clique no botão HTML (onclick)
        window.toggleWidget = toggleWidget;

        console.log('Widget WhatsApp carregado!');
    })();
</script>
</body>
</html><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/layouts/site.blade.php ENDPATH**/ ?>