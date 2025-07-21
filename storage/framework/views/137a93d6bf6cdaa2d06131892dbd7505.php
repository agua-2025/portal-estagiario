 


<?php $__env->startSection('title', 'Classificação Geral de Candidatos - Portal do Estagiário'); ?>


<?php $__env->startSection('content'); ?>
    <div class="relative min-h-screen z-10 py-12"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div data-aos="fade-up">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Transparência</h2>
                <p class="mt-2 text-4xl font-bold text-gray-900 sm:text-5xl">Classificação Geral dos Candidatos</p>
                <div class="mt-4 text-lg text-gray-600">
                    <p>Esta lista é dinâmica e pode ser atualizada conforme as análises são concluídas.</p>
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-12 z-10">
            <?php if($classificacaoPorCurso->isEmpty()): ?>
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center text-gray-600" data-aos="fade-up">
                    <h3 class="text-2xl font-semibold mb-2">Nenhum resultado disponível</h3>
                    <p>A lista de classificação ainda não foi divulgada ou não há candidatos avaliados.</p>
                </div>
            <?php else: ?>
                <?php $__currentLoopData = $classificacaoPorCurso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cursoNome => $candidatos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 mb-8 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl">
                            <h2 class="text-xl font-semibold text-gray-900"><?php echo e($cursoNome); ?></h2>
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
                                        <th class="relative px-4 py-3 text-right"><span class="sr-only">Detalhes</span></th>
                                    </tr>
                                </thead>
                                <?php $__currentLoopData = $candidatos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $candidato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tbody x-data="{ open: false }" class="bg-white divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                                <?php
                                                    // Replicando a lógica de cores de posição da welcome.blade.php
                                                    $positionColorClass = 'bg-gray-400 text-white'; 
                                                    if ($index == 0) $positionColorClass = 'bg-yellow-400 text-white';
                                                    else if ($index == 1) $positionColorClass = 'bg-slate-400 text-white'; 
                                                    else if ($index == 2) $positionColorClass = 'bg-orange-400 text-white'; 
                                                ?>
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full <?php echo e($positionColorClass); ?> font-bold text-sm">
                                                    <?php echo e($index + 1); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo e($candidato->nome); ?></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"><?php echo e(substr($candidato->cpf ?? '', 0, 3)); ?>.***.***-**</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <?php
                                                    $statusClass = 'bg-gray-100 text-gray-800'; // Default
                                                    if ($candidato->status === 'Aprovado') $statusClass = 'status-approved';
                                                    else if ($candidato->status === 'Rejeitado') $statusClass = 'status-rejected';
                                                    else if ($candidato->status === 'Em Análise' || $candidato->status === 'Inscrição Incompleta') $statusClass = 'status-analise'; // Mapear Inscrição Incompleta para Em Análise
                                                ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($statusClass); ?>">
                                                    <?php echo e($candidato->status); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900 text-right"><?php echo e(number_format($candidato->pontuacao_final, 2, ',', '.')); ?></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <button @click="open = !open" class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                                    <span x-show="!open" class="inline-flex items-center">Detalhes <svg class="ml-1 w-3 h-3 transform transition-transform" x-bind:class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                                                    <span x-show="open" class="inline-flex items-center">Esconder <svg class="ml-1 w-3 h-3 transform transition-transform" x-bind:class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr x-show="open" x-transition x-cloak class="bg-gray-50"> 
                                            <td colspan="6" class="px-6 py-4">
                                                <h5 class="font-semibold mb-2 text-sm text-gray-700">Extrato de Pontos:</h5>
                                                <dl class="text-sm text-gray-700 space-y-1">
                                                    <?php $__empty_1 = true; $__currentLoopData = $candidato->pontuacao_detalhes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalhe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <div class="flex justify-between border-b border-gray-200 pb-1 last:border-0">
                                                            <dt class="text-gray-600"><?php echo e($detalhe['nome']); ?>:</dt>
                                                            <dd class="font-medium text-gray-800"><?php echo e(number_format($detalhe['pontos'], 2, ',', '.')); ?> pontos</dd>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <p class="italic text-gray-500">Nenhuma pontuação detalhada registrada.</p>
                                                    <?php endif; ?>
                                                    <div class="flex justify-between pt-2 mt-2 border-t border-gray-300 font-bold">
                                                        <dt>Total Geral:</dt>
                                                        <dd><?php echo e(number_format($candidato->pontuacao_final, 2, ',', '.')); ?> pontos</dd>
                                                    </div>
                                                </dl>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/classificacao/index.blade.php ENDPATH**/ ?>