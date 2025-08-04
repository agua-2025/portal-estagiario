<?php $__env->startSection('title', 'Classificação Geral de Candidatos - Portal do Estagiário'); ?>

<?php $__env->startSection('content'); ?>
    <div class="relative min-h-screen z-10 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div data-aos="fade-up">
                </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-12 z-10 space-y-12">

            
            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Candidatos Convocados</h3>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Candidato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data da Convocação</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Data de Nasc.</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $convocados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($candidato->nome_completo); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($candidato->curso->nome ?? 'N/A'); ?></td>
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

            
            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Classificação Geral</h3>
                
                
                <?php $__empty_1 = true; $__currentLoopData = $homologadosAgrupados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nomeCurso => $candidatos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                        <div class="p-6 bg-gray-100 border-b">
                            <h4 class="text-lg font-bold text-gray-800"><?php echo e($nomeCurso ?: 'Curso não especificado'); ?></h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pos.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidato</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Nasc.</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    
                                    <?php $__currentLoopData = $candidatos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $candidato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($index + 1); ?>º</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo e($candidato->nome_completo); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800"><?php echo e(number_format($candidato->pontuacao_final, 2, ',', '.')); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($candidato->data_nascimento ? $candidato->data_nascimento->format('d/m/Y') : 'N/A'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-6 text-center text-gray-500">
                        Nenhum candidato homologado aguardando convocação.
                    </div>
                <?php endif; ?>
            </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/classificacao/index.blade.php ENDPATH**/ ?>