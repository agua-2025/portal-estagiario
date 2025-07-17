<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificação Geral de Candidatos</title>
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
        <script src="https://cdn.tailwindcss.com"></script>
    
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
    </style>
</head>

<body class="antialiased bg-gray-50 overflow-x-hidden">
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="blob absolute top-0 left-0 w-72 h-72 opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob absolute top-1/2 right-0 w-96 h-96 opacity-15 translate-x-1/3 -translate-y-1/2" style="animation-delay: 2s;"></div>
        <div class="blob absolute bottom-0 left-1/3 w-80 h-80 opacity-10 translate-y-1/2" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative min-h-screen z-10">
                <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
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
                    
                                        <nav class="hidden md:flex space-x-8" data-aos="fade-down">
                        <a href="#cursos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Áreas de Estágio Disponíveis</a>
                        <a href="#documentos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Editais e Documentos</a>
                        <a href="{{ route('classificacao.index') }}" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Classificação</a>
                        <a href="#sobre" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Sobre</a>
                    </nav>

                                        <div class="flex items-center space-x-4" data-aos="fade-left">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">Painel</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Entrar</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">Inscreva-se</a>
                                @endif
                            @endauth
                    </div>
                </div>
            </div>
        </header>

        <main>
                        <section class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                        <div class="text-center mb-16" data-aos="fade-up">
                        <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Transparência</h2>
                        <h1 class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Classificação Geral de Candidatos</h1>
                        <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                            Resultado do processo seletivo, organizado por curso e ordenado por pontuação.
                        </p>
                        <div class="text-sm text-gray-500 mt-2">
                            <p>Esta lista é dinâmica e pode ser atualizada conforme as análises são concluídas.</p>
                            <p>Critério de desempate: maior idade.</p>
                        </div>
                    </div>

                    @if($classificacaoPorCurso->isEmpty())
                        <div class="bg-white rounded-2xl shadow-lg p-8 text-center" data-aos="fade-up">
                            <h3 class="text-lg font-semibold text-gray-900">Nenhum resultado disponível</h3>
                            <p class="text-gray-600 mt-2">A lista de classificação ainda não foi divulgada.</p>
                        </div>
                    @else
                        @foreach($classificacaoPorCurso as $cursoNome => $candidatos)
                            <div class="course-section mb-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                                <div class="course-title bg-gray-50 p-6 border-b border-gray-200">
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $cursoNome }}</h2>
                                </div>
                                
                                <div class="table-container overflow-x-auto">
                                    <table class="w-full results-table">
                                        <thead>
                                            <tr class="border-b border-gray-200">
                                                <th class="py-4 px-6 text-sm font-semibold text-gray-900 text-left">Posição</th>
                                                <th class="py-4 px-6 text-sm font-semibold text-gray-900 text-left">Nome do Candidato</th>
                                                <th class="py-4 px-6 text-sm font-semibold text-gray-900 text-left">CPF</th>
                                                <th class="py-4 px-6 text-sm font-semibold text-gray-900 text-center">Status</th>
                                                <th class="py-4 px-6 text-sm font-semibold text-gray-900 text-right">Pontuação</th>
                                                <th class="py-4 px-6 text-sm font-semibold text-gray-900 text-right"><span class="sr-only">Ações</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($candidatos as $index => $candidato)
                                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 main-row" x-data="{ open: false }" :id="'row-' + $index">
                                                    <td class="py-4 px-6">
                                                        <div class="flex items-center">
                                                            @php
                                                                $positionColor = 'bg-gray-400';
                                                                if ($index == 0) $positionColor = 'bg-yellow-400';
                                                                else if ($index == 1) $positionColor = 'bg-slate-400';
                                                                else if ($index == 2) $positionColor = 'bg-orange-400';
                                                            @endphp
                                                            <div class="w-8 h-8 {{ $positionColor }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                                {{ $index + 1 }}
                                                            </div>
                                                    </td>
                                                    <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $candidato->nome }}</td>
                                                    <td class="py-4 px-6 text-sm text-gray-500">{{ substr($candidato->cpf, 0, 3) }}.***.***-**</td>
                                                    <td class="py-4 px-6 text-center">
                                                        @if($candidato->status === 'Aprovado')
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Aprovado</span>
                                                        @else
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejeitado</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-4 px-6 text-sm font-bold text-blue-600 text-right">{{ number_format($candidato->pontuacao_final, 2, ',', '.') }}</td>
                                                    <td class="py-4 px-6 text-right">
                                                        <button @click="open = !open" class="text-blue-600 hover:text-blue-800 text-xs font-medium transition-colors duration-200">
                                                            <span x-show="!open">Detalhes</span>
                                                            <span x-show="open">Esconder</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr x-show="open" x-transition :id="'details-' + $index" class="details-row">
                                                    <td colspan="6" class="bg-gray-50 p-6">
                                                        <dl class="details-list space-y-2">
                                                            <h4 class="font-semibold text-xs uppercase tracking-wider text-gray-900 mb-2">Extrato de Pontos:</h4>
                                                            @forelse($candidato->pontuacao_detalhes ?? [] as $detalhe)
                                                                <div class="flex justify-between py-1 border-b border-gray-200 last:border-0">
                                                                    <dt class="text-gray-600">{{ $detalhe['nome'] }}</dt>
                                                                    <dd class="font-medium text-gray-900">{{ number_format($detalhe['pontos'], 2, ',', '.') }}</dd>
                                                                </div>
                                                            @empty
                                                                <p class="italic text-gray-500">Nenhuma pontuação registrada.</p>
                                                            @endforelse
                                                        </dl>
                                                    </td>
                                                </tr>
                                            @endforeach
                    @endif
                </div>
            </section>
        </main>

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
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
                    © 2025 Portal do Estagiário. Todos os direitos reservados.
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
</body>
</html>