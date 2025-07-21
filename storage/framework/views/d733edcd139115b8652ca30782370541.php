<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Gestão de Regras de Pontuação
                        </h2>
                        <a href="<?php echo e(route('admin.tipos-de-atividade.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Nova Regra
                        </a>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p><?php echo e(session('success')); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p><?php echo e(session('error')); ?></p>
                        </div>
                    <?php endif; ?>


                    <!-- TABELA DE REGRAS, MAIS COMPLETA E INTELIGENTE -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome da Regra</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo de Cálculo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Regra de Pontuação</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pontuação Máxima</th>
                                    <th class="relative px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $atividades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atividade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($atividade->nome); ?></td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize"><?php echo e($atividade->unidade_medida); ?></td>
                                        
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php if(str_contains(strtolower($atividade->nome), 'aproveitamento acadêmico') && $atividade->unidade_medida == 'fixo'): ?>
                                                <?php echo e($atividade->pontos_por_unidade); ?> pontos (se média >= <?php echo e($atividade->divisor_unidade ?? 'N/A'); ?>)
                                            <?php elseif($atividade->unidade_medida == 'fixo'): ?>
                                                <?php echo e($atividade->pontos_por_unidade); ?> pontos
                                            <?php else: ?>
                                                <?php echo e($atividade->pontos_por_unidade); ?> ponto(s) a cada <?php echo e($atividade->divisor_unidade); ?> <?php echo e($atividade->unidade_medida); ?>

                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($atividade->pontuacao_maxima ?? 'N/A'); ?>

                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="<?php echo e(route('admin.tipos-de-atividade.edit', $atividade->id)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</a>
                                            <form class="inline-block" method="POST" action="<?php echo e(route('admin.tipos-de-atividade.destroy', $atividade->id)); ?>" onsubmit="return confirm('Tem certeza que deseja apagar esta regra?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900">Apagar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Nenhuma regra de pontuação cadastrada.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/tipos-de-atividade/index.blade.php ENDPATH**/ ?>