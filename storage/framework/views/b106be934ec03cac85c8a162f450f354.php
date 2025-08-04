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
            Convocar Candidato: <?php echo e($candidato->nome_completo); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <form action="<?php echo e(route('admin.candidatos.convocar', $candidato)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-6">

                            
                            <div>
                                <label for="local_lotacao" class="block text-sm font-medium text-gray-700">Local de Lotação</label>
                               <input type="text" name="lotacao_local" id="lotacao_local" value="<?php echo e(old('lotacao_local')); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="lotacao_chefia" class="block text-sm font-medium text-gray-700">Chefia Imediata</label>
                                <input type="text" name="lotacao_chefia" id="lotacao_chefia" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contrato_data_inicio" class="block text-sm font-medium text-gray-700">Data de Início do Contrato</label>
                                    <input type="date" name="contrato_data_inicio" id="contrato_data_inicio" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="contrato_data_fim" class="block text-sm font-medium text-gray-700">Data Final do Contrato</label>
                                    <input type="date" name="contrato_data_fim" id="contrato_data_fim" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>

                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
                                <div>
                                    <label for="prorrogacao_data_inicio" class="block text-sm font-medium text-gray-700">Início da Prorrogação (Opcional)</label>
                                    <input type="date" name="prorrogacao_data_inicio" id="prorrogacao_data_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="prorrogacao_data_fim" class="block text-sm font-medium text-gray-700">Término da Prorrogação (Opcional)</label>
                                    <input type="date" name="prorrogacao_data_fim" id="prorrogacao_data_fim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>

                            
                            <div>
                                <label for="lotacao_observacoes" class="block text-sm font-medium text-gray-700">Observações (Opcional)</label>
                                <textarea name="lotacao_observacoes" id="lotacao_observacoes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4">
                            <a href="<?php echo e(route('admin.candidatos.ranking')); ?>" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Salvar Convocação
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
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/candidatos/atribuir-vaga.blade.php ENDPATH**/ ?>