<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Classificação Completa - Portal do Estagiário</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg { /* Cor de fundo principal se precisar */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Estilos da tabela adaptados do welcome.blade.php */
        /* Removi a maioria dos estilos hardcoded CSS e usarei Tailwind */

        /* Cores de status, replicando o welcome.blade.php */
        .status-approved { background-color: #d4edda; color: #155724; } /* bg-green-100 text-green-800 */
        .status-rejected { background-color: #f8d7da; color: #721c24; } /* bg-red-100 text-red-800 */
        .status-analise { background-color: #fff3cd; color: #856404; } /* bg-yellow-100 text-yellow-800 */
    </style>
</head>

<body class="antialiased bg-gray-50 overflow-x-hidden">
    <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex-shrink-0" data-aos="fade-right">
                    <a href="{{ route('welcome') }}" class="flex items-center space-x-3">
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
                    <a href="{{ route('welcome') }}#cursos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Cursos Disponíveis</a>
                    <a href="{{ route('welcome') }}#documentos" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Editais e Documentos</a>
                    <a href="{{ route('classificacao.index') }}" class="text-base font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200">Classificação</a> <a href="{{ route('welcome') }}#sobre" class="text-base font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">Sobre</a>
                </nav>

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

    <main class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8" data-aos="fade-right">
                <a href="{{ route('welcome') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 font-medium">
                    <svg class="w-4 h-4 mr-1 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    Voltar para a Página Inicial
                </a>
            </div>

            <div class="text-center mb-12" data-aos="fade-up">
                <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">Classificação Completa dos Candidatos</h1>
                <p class="mt-4 text-xl text-gray-600">
                    Resultado do processo seletivo da Prefeitura de Mirassol D'Oeste, organizado por curso e ordenado por pontuação.
                </p>
                <div class="mt-2 text-sm text-gray-500">
                    <p>Esta lista é dinâmica e pode ser atualizada conforme as análises são concluídas.</p>
                    <p>Critério de desempate: maior idade.</p>
                </div>
            </div>

            @if($classificacaoPorCurso->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center text-gray-600" data-aos="fade-up">
                    <h3 class="text-2xl font-semibold mb-2">Nenhum resultado disponível</h3>
                    <p>A lista de classificação ainda não foi divulgada ou não há candidatos avaliados.</p>
                </div>
            @else
                @foreach($classificacaoPorCurso as $cursoNome => $candidatos)
                    <div class="bg-white rounded-2xl shadow-lg mb-8" data-aos="fade-up" data-aos-delay="100">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $cursoNome }}</h2>
                        </div>
                        
                        <div class="overflow-x-auto p-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pos.</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Candidato</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                                        <th class="relative px-4 py-3 text-right"><span class="sr-only">Ações</span></th>
                                    </tr>
                                </thead>
                                @foreach($candidatos as $index => $candidato)
                                    <tbody x-data="{ open: false }" class="bg-white divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $index + 1 }}º</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $candidato->nome }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ substr($candidato->cpf, 0, 3) }}.***.***-**</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @php
                                                    $statusClass = 'bg-gray-100 text-gray-800'; // Default
                                                    if ($candidato->status === 'Aprovado') $statusClass = 'status-approved';
                                                    else if ($candidato->status === 'Rejeitado') $statusClass = 'status-rejected';
                                                    else if ($candidato->status === 'Em Análise') $statusClass = 'status-analise'; // Se houver outros status
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                    {{ $candidato->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900 text-right">{{ number_format($candidato->pontuacao_final, 2, ',', '.') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <button @click="open = !open" class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                                    <span x-show="!open" class="inline-flex items-center">Detalhes <svg class="ml-1 w-3 h-3 transform transition-transform" x-bind:class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                                                    <span x-show="open" class="inline-flex items-center">Esconder <svg class="ml-1 w-3 h-3 transform transition-transform" x-bind:class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr x-show="open" x-transition class="bg-gray-50">
                                            <td colspan="6" class="px-6 py-4">
                                                <h5 class="font-semibold mb-2 text-sm text-gray-700">Extrato de Pontos:</h5>
                                                <dl class="text-sm text-gray-700 space-y-1">
                                                    @forelse($candidato->pontuacao_detalhes ?? [] as $detalhe)
                                                        <div class="flex justify-between border-b border-gray-200 pb-1 last:border-0">
                                                            <dt class="text-gray-600">{{ $detalhe['nome'] }}:</dt>
                                                            <dd class="font-medium text-gray-800">{{ number_format($detalhe['pontos'], 2, ',', '.') }} pontos</dd>
                                                        </div>
                                                    @empty
                                                        <p class="italic text-gray-500">Nenhuma pontuação detalhada registrada.</p>
                                                    @endforelse
                                                    <div class="flex justify-between pt-2 mt-2 border-t border-gray-300 font-bold">
                                                        <dt>Total Geral:</dt>
                                                        <dd>{{ number_format($candidato->pontuacao_final, 2, ',', '.') }} pontos</dd>
                                                    </div>
                                                </dl>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <a href="{{ route('welcome') }}" class="flex items-center justify-center md:justify-start space-x-3 mb-4">
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
                        <li><a href="{{ route('welcome') }}#cursos" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Cursos Disponíveis</a></li>
                        <li><a href="{{ route('welcome') }}#documentos" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Editais e Documentos</a></li>
                        <li><a href="{{ route('classificacao.index') }}" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Classificação</a></li>
                        <li><a href="{{ route('welcome') }}#sobre" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">Sobre Nós</a></li>
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
                &copy; 2025 Portal do Estagiário. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-back',
        });
    </script>
</body>
</html>