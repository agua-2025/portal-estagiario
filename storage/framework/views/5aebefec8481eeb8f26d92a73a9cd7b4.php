 

<?php $__env->startSection('title', 'Portal do Estagiário - O seu futuro profissional começa aqui.'); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="relative gradient-bg min-h-screen flex items-center justify-center text-white overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        
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
                <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-full text-purple-700 bg-white hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl hover-scale">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Iniciar Inscrição
                </a>
                <a href="<?php echo e(route('classificacao.index')); ?>" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-full text-white border-2 border-white hover:bg-white hover:text-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
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
        
        <div class="absolute top-20 left-10 floating">
            <div class="w-20 h-20 bg-white/10 rounded-full"></div>
        </div>
        <div class="absolute bottom-20 right-10 floating" style="animation-delay: 1s;">
            <div class="w-16 h-16 bg-white/10 rounded-full"></div>
        </div>
    </section>

    
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
                     
                    <div class="relative overflow-hidden">
                        
                        <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-25 w-full h-full"></div>
                        
                        <img src="https://images.pexels.com/photos/7129700/pexels-photo-7129700.jpeg" alt="Estudantes" class="relative w-full h-96 object-cover rounded-2xl shadow-2xl max-w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section id="cursos" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Áreas de Atuação</h2>
                <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Seu Conhecimento Tem Valor Aqui</p>
                <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">
                    Estamos construindo um banco de talentos com os perfis mais promissores para diversas áreas. **Cadastre-se, pontue seu perfil** e esteja preparado para as vagas que surgirem nos setores da Prefeitura.
                </p>
            </div>
            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php $__empty_1 = true; $__currentLoopData = $cursos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover-scale flex flex-col h-full">
                        <div class="p-8 flex flex-col flex-grow">
                            <div class="flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white mx-auto mb-6">
                                <?php if($curso->icone_svg): ?>
                                    <?php echo $curso->icone_svg; ?>

                                <?php else: ?>
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2"><?php echo e($curso->nome); ?></h3>
                            <p class="text-gray-600 text-center text-sm mb-4"><?php echo e($curso->descricao ?? 'Detalhes sobre o curso e sua área de atuação.'); ?></p>
                            <div class="flex justify-center mt-auto">
                                <a href="<?php echo e(route('cursos.show', $curso->id)); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                    Saber Mais &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center text-gray-600 text-lg py-10">
                        Nenhuma área de atuação disponível no momento.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    
    <section id="classificacao" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Seu Desempenho Importa</h2>
                <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Acompanhe Sua Classificação</p>
                <p class="mt-4 max-w-3xl mx-auto text-xl text-gray-600">Seu perfil é pontuado de forma transparente com base nas suas qualificações e experiências. Acesse seu painel, atualize seu cadastro constantemente e veja sua pontuação e posição no ranking crescerem!
                </p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8" data-aos="fade-up" data-aos-delay="200">
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Posição</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Candidato</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Área de Atuação</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Instituição</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Pontuação</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $candidatosClassificacao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $candidato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-b border-gray-100 hover:bg-white transition-colors duration-200">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <?php
                                                $positionColor = 'bg-gray-400';
                                                if ($index == 0) $positionColor = 'bg-yellow-400';
                                                else if ($index == 1) $positionColor = 'bg-slate-400';
                                                else if ($index == 2) $positionColor = 'bg-orange-400';
                                            ?>
                                            <div class="w-8 h-8 <?php echo e($positionColor); ?> rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                <?php echo e($index + 1); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($candidato->nome_completo); ?></div>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-900">
                                        <?php echo e($candidato->curso->nome ?? 'Não Informado'); ?>

                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-900">
                                        <?php echo e($candidato->instituicao->nome ?? 'Não Informada'); ?>

                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-900">
                                        <?php echo e(number_format($candidato->pontuacao_final, 1)); ?>

                                    </td>
                                    <td class="py-4 px-6">
                                        <?php
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            if ($candidato->status == 'Aprovado') $statusClass = 'bg-green-100 text-green-800';
                                            else if ($candidato->status == 'Em Análise') $statusClass = 'bg-yellow-100 text-yellow-800';
                                            else if ($candidato->status == 'Rejeitado') $statusClass = 'bg-red-100 text-red-800';
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                                            <?php echo e($candidato->status); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-600">Nenhum candidato classificado no momento.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-8 flex justify-center">
                    <a href="<?php echo e(route('classificacao.index')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                        Ver Classificação Completa
                        <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    
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
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                            
                                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Cronograma de Chamamento para Estágio</h3>
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
        </section>

        
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

        
        <section class="py-24 gradient-bg text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div data-aos="fade-up">
                    <h2 class="text-4xl font-bold mb-6">Pronto para subir no ranking e conquistar seu estágio?</h2>
                    <p class="text-xl mb-8 text-gray-100">Junte-se a centenas de estudantes que já pontuam pelas melhores oportunidades na Prefeitura.</p>
                    <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-full text-purple-700 bg-white hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl hover-scale">
                        Inscreva-se Agora
                        <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        
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
    <?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/welcome.blade.php ENDPATH**/ ?>