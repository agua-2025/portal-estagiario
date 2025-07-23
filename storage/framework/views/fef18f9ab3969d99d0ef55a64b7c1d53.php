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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cadastrar Nova Regra de Pontuação
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900" x-data="{ 
                    unidadeMedida: '<?php echo e(old('unidade_medida', 'fixo')); ?>', // Padrão para 'fixo'
                    nomeRegra: '<?php echo e(old('nome', '')); ?>'
                }">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Cadastrar Nova Regra de Pontuação
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">Defina aqui as atividades e como elas serão pontuadas.</p>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Opa! Algo deu errado.</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('admin.tipos-de-atividade.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="space-y-6">
                            <!-- Nome da Regra -->
                            <div>
                                <label for="nome" class="block font-medium text-sm text-gray-700">Nome da Regra</label>
                                <input id="nome" name="nome" type="text" x-model="nomeRegra" value="<?php echo e(old('nome')); ?>" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required autofocus>
                            </div>

                            <!-- Descrição -->
                            <div>
                                <label for="descricao" class="block font-medium text-sm text-gray-700">Descrição (Opcional)</label>
                                <textarea id="descricao" name="descricao" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"><?php echo e(old('descricao')); ?></textarea>
                            </div>

                            <!-- Tipo de Cálculo -->
                            <div>
                                <label for="unidade_medida" class="block font-medium text-sm text-gray-700">Tipo de Cálculo</label>
                                <select id="unidade_medida" name="unidade_medida" x-model="unidadeMedida" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="fixo">Pontuação Fixa</option>
                                    <option value="horas">Por Carga Horária</option>
                                    <option value="meses">Por Duração (meses)</option>
                                    <option value="semestre">Por Semestre</option> 
                                </select>
                            </div>
                            
                            <!-- Campos Dinâmicos -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                
                                <div>
                                    <label for="pontos_por_unidade" class="block font-medium text-sm text-gray-700" 
                                            x-text="unidadeMedida === 'fixo' ? 'Pontos' : 'Pontos por Unidade'"></label>
                                    <input id="pontos_por_unidade" name="pontos_por_unidade" type="number" step="0.01" value="<?php echo e(old('pontos_por_unidade')); ?>" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>

                                
                                
                                <div x-show="unidadeMedida === 'horas' || unidadeMedida === 'meses' || unidadeMedida === 'semestre' || (unidadeMedida === 'fixo' && nomeRegra.toLowerCase().includes('aproveitamento acadêmico'))" x-transition>
                                    <label for="divisor_unidade" class="block font-medium text-sm text-gray-700"
                                            x-text="unidadeMedida === 'fixo' ? 'Nota de Corte (Média Mínima)' : 'A cada ' + (unidadeMedida === 'semestre' ? 'semestre(s)' : unidadeMedida)"></label>
                                    <input id="divisor_unidade" name="divisor_unidade" type="number" step="0.01" value="<?php echo e(old('divisor_unidade')); ?>" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="text-xs text-gray-500 mt-1" x-show="unidadeMedida !== 'fixo'">Ex: 1 ponto a cada **30** horas.</p>
                                </div>

                                
                                
                                <div x-show="unidadeMedida === 'horas' || unidadeMedida === 'meses' || unidadeMedida === 'semestre'" x-transition>
                                    <label for="pontuacao_maxima" class="block font-medium text-sm text-gray-700">Pontuação Máxima (Opcional)</label>
                                    <input id="pontuacao_maxima" name="pontuacao_maxima" type="number" value="<?php echo e(old('pontuacao_maxima')); ?>" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end mt-6 pt-6 border-t">
                            <a href="<?php echo e(route('admin.tipos-de-atividade.index')); ?>" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Salvar Regra
                            </button>
                        </div>
                    </form>

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
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/tipos-de-atividade/create.blade.php ENDPATH**/ ?>