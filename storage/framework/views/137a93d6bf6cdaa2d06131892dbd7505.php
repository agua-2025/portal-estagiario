<?php $__env->startSection('title', 'Classificação Geral de Candidatos - Portal do Estagiário'); ?>

<?php $__env->startSection('content'); ?>
    <div class="relative min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Principal -->
            <div class="text-center mb-10" data-aos="fade-up">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Classificação Geral de Candidatos</h1>
                <p class="text-gray-600">Portal do Estagiário</p>
            </div>

            <!-- SEÇÃO DE CONVOCADOS -->
            <div data-aos="fade-up" data-aos-delay="100" class="mb-12">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header da Seção -->
                    <div class="bg-green-50 border-b border-green-200 px-6 py-4">
                        <h2 class="text-xl font-semibold text-green-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Candidatos Convocados
                        </h2>
                    </div>
                    
                    <!-- Tabela -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th style="width: 35%;" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Candidato</th>
                                    <th style="width: 25%;" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th style="width: 20%;" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data da Convocação</th>
                                    <th style="width: 20%;" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Nasc.</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $convocados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($candidato->nome_completo); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($candidato->curso->nome ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($candidato->convocado_em ? $candidato->convocado_em->format('d/m/Y') : 'N/A'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($candidato->data_nascimento ? $candidato->data_nascimento->format('d/m/Y') : 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum candidato convocado até o momento.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO DE CLASSIFICAÇÃO GERAL -->
            <div data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header da Seção -->
                    <div class="bg-blue-50 border-b border-blue-200 px-6 py-4">
                        <h2 class="text-xl font-semibold text-blue-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Classificação Geral
                        </h2>
                    </div>
                    
                    <!-- TABELA ÚNICA PARA TODOS OS CURSOS -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <colgroup>
                                <col style="width: 10%;">
                                <col style="width: 40%;">
                                <col style="width: 25%;">
                                <col style="width: 25%;">
                            </colgroup>
                            
                            <?php $__empty_1 = true; $__currentLoopData = $homologadosAgrupados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nomeCurso => $candidatos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <!-- Header do Curso como linha da tabela -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="4" class="px-6 py-4 text-left">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($nomeCurso ?: 'Curso não especificado'); ?></h3>
                                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                                    <?php echo e(count($candidatos)); ?> candidato<?php echo e(count($candidatos) > 1 ? 's' : ''); ?>

                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pos.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidato</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Nasc.</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $candidatos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $candidato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-medium
                                                    <?php echo e($index === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($index === 1 ? 'bg-gray-100 text-gray-700' : 
                                                       ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-blue-50 text-blue-600'))); ?>">
                                                    <?php echo e($index + 1); ?>º
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($candidato->nome_completo); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <?php echo e(number_format($candidato->pontuacao_final, 2, ',', '.')); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($candidato->data_nascimento ? $candidato->data_nascimento->format('d/m/Y') : 'N/A'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-gray-500">
                                            Nenhum candidato homologado aguardando convocação.
                                        </td>
                                    </tr>
                                </tbody>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/classificacao/index.blade.php ENDPATH**/ ?>