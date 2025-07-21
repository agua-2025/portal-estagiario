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
            Interpor Recurso da Inscrição
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <?php if($candidato->recurso_texto): ?>
                        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="text-lg font-bold text-blue-800">Recurso Enviado</h3>
                            <p class="text-sm text-gray-700 mt-2">Seu recurso foi enviado com sucesso em <?php echo e($candidato->updated_at->format('d/m/Y \à\s H:i')); ?> e está aguardando análise pela Comissão Organizadora.</p>
                            <div class="mt-4 p-4 bg-white border rounded-md text-sm">
                                <p class="font-semibold">Seu argumento:</p>
                                <p class="mt-1 text-gray-600 whitespace-pre-wrap"><?php echo e($candidato->recurso_texto); ?></p>
                            </div>
                            <a href="<?php echo e(route('dashboard')); ?>" class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline">Voltar ao Painel</a>
                        </div>
                    <?php else: ?>
                        <div class="p-6 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                            <h3 class="text-lg font-bold text-red-800">Sua Inscrição foi Rejeitada</h3>
                            <div class="mt-2 p-4 bg-white border border-red-200 rounded-md text-sm">
                                <p class="font-semibold">Motivo da Rejeição informado pela Comissão:</p>
                                <p class="mt-1 text-gray-700"><?php echo e($candidato->admin_observacao); ?></p>
                            </div>

                            <?php if($candidato->recurso_prazo_ate && \Carbon\Carbon::now()->lt($candidato->recurso_prazo_ate)): ?>
                                <p class="mt-4 text-sm text-gray-800">
                                    Você tem o direito de contestar esta decisão. Por favor, apresente seu recurso no campo abaixo até a data limite.
                                    <br>
                                    <span class="font-bold">Prazo Final para Recurso:</span> <?php echo e(\Carbon\Carbon::parse($candidato->recurso_prazo_ate)->format('d/m/Y \à\s H:i')); ?>

                                </p>

                                <form action="<?php echo e(route('candidato.recurso.store')); ?>" method="POST" class="mt-4">
                                    <?php echo csrf_field(); ?>
                                    <div>
                                        <label for="recurso_texto" class="block text-sm font-medium text-gray-700">Apresente seus argumentos (mínimo 50 caracteres)</label>
                                        <textarea name="recurso_texto" id="recurso_texto" rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required minlength="50"><?php echo e(old('recurso_texto')); ?></textarea>
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
                                    <div class="mt-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                              <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                              <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            Enviar Recurso para Análise
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <p class="mt-4 font-semibold text-red-700">O prazo para interpor recurso para esta inscrição já encerrou.</p>
                            <?php endif; ?>
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
<?php endif; ?>
<?php /**PATH C:\laragon\www\portal-estagiario\resources\views/candidato/recurso/create.blade.php ENDPATH**/ ?>