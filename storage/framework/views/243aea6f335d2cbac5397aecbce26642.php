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
            Interpor Recurso de Classificação
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <?php
                        $candidato = Auth::user()->candidato;
                        $recursoPendente = false;
                        $recursoMaisRecente = null;

                        if (!empty($candidato->recurso_historico)) {
                            $recursoMaisRecente = $candidato->recurso_historico[0];
                            if (empty($recursoMaisRecente['decisao_admin'])) {
                                $recursoPendente = true;
                            }
                        }
                    ?>

                    
                    <?php if($recursoPendente): ?>
                        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="text-lg font-bold text-blue-800">Recurso Enviado</h3>
                            <p class="text-sm text-gray-700 mt-2">Seu recurso foi enviado com sucesso em <?php echo e(\Carbon\Carbon::parse($recursoMaisRecente['data_envio'])->format('d/m/Y \à\s H:i')); ?> e está aguardando análise pela Comissão Organizadora.</p>
                            <div class="mt-4 p-4 bg-white border rounded-md text-sm">
                                <p class="font-semibold">Seu argumento:</p>
                                <p class="mt-1 text-gray-600 whitespace-pre-wrap"><?php echo e($recursoMaisRecente['argumento_candidato']); ?></p>
                            </div>
                            <a href="<?php echo e(route('dashboard')); ?>" class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline">Voltar ao Painel</a>
                        </div>

                    
                    <?php elseif($candidato && $candidato->pode_interpor_recurso): ?>
                        
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Formulário de Recurso de Classificação
                            </h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Utilize o campo abaixo para descrever de forma clara e objetiva os motivos do seu recurso contra a classificação final. Fundamente a sua argumentação com base nos critérios do edital.
                            </p>
                        </div>

                        
                        <div class="border-t border-gray-200 mt-6 pt-6">
                            <p class="text-sm text-gray-800 mb-4">
                                <span class="font-bold">Prazo Final para Recurso:</span> 
                                
                                
                                <?php echo e($prazoFinal->format('d/m/Y \à\s H:i')); ?>

                            </p>

                            <form action="<?php echo e(route('candidato.recurso.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label for="recurso_texto" class="block text-sm font-medium text-gray-700">Apresente seus argumentos (mínimo 50 caracteres)</label>
                                    <textarea name="recurso_texto" id="recurso_texto" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required minlength="50"><?php echo e(old('recurso_texto')); ?></textarea>
                                    <?php $__errorArgs = ['recurso_texto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-sm text-red-600 mt-2"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mt-6 flex items-center justify-end gap-4">
                                    <a href="<?php echo e(route('dashboard')); ?>" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-semibold">
                                        Enviar Recurso para Análise
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                    
                    <?php else: ?>
                        <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                            <h3 class="text-lg font-bold text-yellow-800">Acesso Indisponível</h3>
                            <p class="mt-2 text-sm text-gray-700">O período para interpor recurso de classificação não está disponível ou já encerrou.</p>
                             <a href="<?php echo e(route('dashboard')); ?>" class="mt-4 inline-block text-sm font-semibold text-yellow-600 hover:underline">Voltar ao Painel</a>
                        </div>
                    <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/candidato/recurso/create.blade.php ENDPATH**/ ?>