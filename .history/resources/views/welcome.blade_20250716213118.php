<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal do Estagiário - Melhorias</title>
    
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

        /* MELHORIAS ADICIONAIS */
        .mobile-menu {
            display: none;
        }
        
        .mobile-menu.active {
            display: block;
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .scroll-to-top.visible {
            opacity: 1;
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

    <!-- Notification Toast -->
    <div id="notification" class="notification bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span id="notification-text">Inscrição realizada com sucesso!</span>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-all duration-300">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <div class="relative min-h-screen z-10">
        <!-- CABEÇALHO MELHORADO -->
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
                    
                    <!-- NAVEGAÇÃO DESKTOP -->
                    <nav class="hidden md:flex space-x-8" data-aos="fade-down">
                        <a href="#cursos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Áreas de Estágio</a>
                        <a href="#documentos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Documentos</a>
                        <a href="#classificacao" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Classificação</a>
                        <a href="#sobre" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Sobre</a>
                    </nav>

                    <!-- BOTÕES DE AÇÃO -->
                    <div class="flex items-center space-x-4" data-aos="fade-left">
                        <!-- Menu Mobile Toggle -->
                        <button id="mobile-menu-toggle" class="md:hidden p-2 text-gray-600 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <div class="hidden md:flex items-center space-x-4">
                            <a href="#" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Entrar</a>
                            <a href="#" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                Inscreva-se
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- MENU MOBILE -->
                <div id="mobile-menu" class="mobile-menu md:hidden py-4 border-t border-gray-200">
                    <div class="space-y-4">
                        <a href="#cursos" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Áreas de Estágio</a>
                        <a href="#documentos" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Documentos</a>
                        <a href="#classificacao" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Classificação</a>
                        <a href="#sobre" class="block text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Sobre</a>
                        <div class="pt-4 border-t border-gray-200 space-y-2">
                            <a href="#" class="block text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Entrar</a>
                            <a href="#" class="block px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl w-fit">
                                Inscreva-se
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MELHORIAS PRINCIPAIS -->
        <main>
            <!-- SEÇÃO PRINCIPAL COM MELHORIAS -->
            <section class="relative gradient-bg min-h-screen flex items-center justify-center text-white overflow-hidden">
                <div class="absolute inset-0 bg-black/20"></div>
                
                <!-- Breadcrumb/Progress Indicator -->
                <div class="absolute top-24 left-1/2 transform -translate-x-1/2 z-20">
                    <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                        <span class="text-sm text-white/80">Inscrições Abertas</span>
                    </div>
                </div>
                
                <div class="relative z-10 max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-shadow mb-6">
                            <span class="block">O seu futuro profissional</span>
                            <span class="block text-yellow-300">começa aqui.</span>
                        </h1>
                        <p class="mt-6 max-w-3xl mx-auto text-xl md:text-2xl text-gray-100 leading-relaxed">
                            Conectamos talentos promissores às melhores oportunidades de estágio na 
                            <span class="text-yellow-300 font-semibold">Prefeitura de Mirassol D'Oeste</span>.
                        </p>
                    </div>
                    
                    <!-- CTA Melhorado -->
                    <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
                        <button onclick="showNotification('Redirecionando para inscrição...', 'info')" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-full text-purple-700 bg-white hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl hover-scale">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Iniciar Inscrição
                        </button>
                        <a href="#classificacao" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-full text-white border-2 border-white hover:bg-white hover:text-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Ver Classificação
                        </a>
                    </div>

                    <!-- ESTATÍSTICAS MELHORADAS -->
                    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="400">
                        <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/20 transition-all duration-300">
                            <div class="text-4xl font-bold text-yellow-300 stats-counter">400+</div>
                            <div class="text-gray-200 mt-2">Estagiários Contratados</div>
                            <div class="text-gray-300 text-sm mt-1">Nos últimos 3 anos</div>
                        </div>
                        <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/20 transition-all duration-300">
                            <div class="text-4xl font-bold text-yellow-300 stats-counter">30+</div>
                            <div class="text-gray-200 mt-2">Setores Atendidos</div>
                            <div class="text-gray-300 text-sm mt-1">Em toda a prefeitura</div>
                        </div>
                        <div class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/20 transition-all duration-300">
                            <div class="text-4xl font-bold text-yellow-300 stats-counter">10+</div>
                            <div class="text-gray-200 mt-2">Áreas Disponíveis</div>
                            <div class="text-gray-300 text-sm mt-1">Para diferentes cursos</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO DE BUSCA RÁPIDA -->
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12" data-aos="fade-up">
                        <h2 class="text-3xl font-bold text-gray-900">Encontre sua área de interesse</h2>
                        <p class="text-gray-600 mt-4">Pesquise por curso ou área de atuação</p>
                    </div>
                    
                    <div class="max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                        <div class="relative">
                            <input 
                                type="text" 
                                placeholder="Ex: Direito, Engenharia, Administração..." 
                                class="w-full px-6 py-4 text-lg border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-lg"
                                id="searchInput"
                            >
                            <button class="absolute right-2 top-2 bottom-2 px-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Sugestões de busca -->
                        <div class="mt-4 flex flex-wrap justify-center gap-2">
                            <span class="text-sm text-gray-500">Populares:</span>
                            <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">Direito</button>
                            <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">Administração</button>
                            <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">Engenharia</button>
                            <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">Psicologia</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Resto do conteúdo permanece igual... -->
            <!-- ... (outras seções) ... -->
        </main>
    </div>

    <!-- SCRIPTS MELHORADOS -->
    <script>
        // Inicialização do AOS
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-back',
        });

        // Menu Mobile Toggle
        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('active');
        });

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll to Top Button
        const scrollToTopBtn = document.getElementById('scrollToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Notification System
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notification-text');
            
            // Definir cor baseada no tipo
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500',
                warning: 'bg-yellow-500'
            };
            
            notification.className = `notification ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg`;
            notificationText.textContent = message;
            
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Contador de estatísticas melhorado
        function animateNumbers() {
            document.querySelectorAll('.stats-counter').forEach(counter => {
                const target = parseInt(counter.textContent.replace('+', ''));
                let current = 0;
                const increment = target / 100;
                const duration = 2000; // 2 segundos
                const stepTime = duration / 100;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current) + '+';
                        setTimeout(updateCounter, stepTime);
                    } else {
                        counter.textContent = target + '+';
                    }
                };
                updateCounter();
            });
        }

        // Observer para estatísticas
        const statsSection = document.querySelector('.stats-counter')?.closest('.grid');
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

        // Busca simples
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            // Aqui você pode implementar a lógica de busca
            console.log('Buscando por:', query);
        });

        // Loading states para melhor UX
        function showLoading(element) {
            element.classList.add('skeleton');
            element.innerHTML = '';
        }

        function hideLoading(element, originalContent) {
            element.classList.remove('skeleton');
            element.innerHTML = originalContent;
        }

        // Lazy loading para imagens
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('skeleton');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => {
            img.classList.add('skeleton');
            imageObserver.observe(img);
        });

        // Feedback visual para forms
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-500');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-500');
            });
        });
    </script>
</body>
</html>