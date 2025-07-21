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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Meus Documentos</h2>
                        <p class="mt-1 text-sm text-gray-600">Envie os documentos necessários para validar a sua inscrição.</p>
                    </div>

                    
                    <?php if(!$candidato->isProfileComplete()): ?>
                        
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                            <p class="font-bold">Ação Necessária</p>
                            <p>Por favor, complete 100% do seu perfil de dados cadastrais na página "Meu Currículo" antes de enviar os seus documentos.</p>
                            
                            <div class="mt-3 p-2 border border-red-300 bg-red-50 text-red-900 text-xs rounded">
                                <strong>Informação de Debug:</strong>
                                <p>O sistema identificou que o campo "<strong><?php echo e($candidato->getFirstIncompleteField() ?? 'Não foi possível identificar'); ?></strong>" está em falta.</p>
                                <p>Por favor, volte ao seu perfil e verifique se este campo está preenchido corretamente.</p>
                            </div>

                            <a href="<?php echo e(route('candidato.profile.edit')); ?>" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Completar Meu Perfil
                            </a>
                        </div>

                    <?php else: ?>

                        
                        <?php if($candidato->status === 'Inscrição Incompleta' && !empty($candidato->admin_observacao)): ?>
                            <div class="p-4 mb-6 border-l-4 border-red-500 bg-red-50 text-red-800 rounded-lg" role="alert">
                                <h3 class="font-bold text-lg">Correção Necessária!</h3>
                                <p class="mt-1">A Comissão Organizadora solicitou uma correção no seu cadastro, conforme descrito abaixo. O andamento do seu processo seletivo permanecerá suspenso até que o ajuste seja devidamente realizado.</p>
                            </div>
                        <?php endif; ?>
                        

                        
                        <?php if(session('success')): ?>
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                                <p><?php echo e(session('success')); ?></p>
                            </div>
                        <?php endif; ?>
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

                        
                        <div class="space-y-4">
                            <?php $__currentLoopData = $documentosNecessarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">
                                    
                                    <div class="flex-grow text-center sm:text-left w-full">
                                        <p class="font-semibold"><?php echo e($nome); ?></p>
                                        <?php
                                            $documentoEnviado = $documentosEnviados->get($tipo);
                                        ?>

                                        <?php if($documentoEnviado): ?>
                                            <span class="text-xs font-medium capitalize px-2.5 py-0.5 rounded-full
                                                <?php if($documentoEnviado->status == 'aprovado'): ?> bg-green-100 text-green-800 <?php endif; ?>
                                                <?php if($documentoEnviado->status == 'enviado'): ?> bg-blue-100 text-blue-800 <?php endif; ?>
                                                <?php if($documentoEnviado->status == 'rejeitado'): ?> bg-red-100 text-red-800 <?php endif; ?>
                                            ">
                                                Status: <?php echo e($documentoEnviado->status); ?>

                                            </span>

                                            <?php if($documentoEnviado->status === 'rejeitado' && !empty($documentoEnviado->motivo_rejeicao)): ?>
                                                <div class="mt-2 p-2 text-xs text-red-800 bg-red-50 rounded-md border border-red-200">
                                                    <strong class="font-bold">Motivo da Rejeição:</strong> <?php echo e($documentoEnviado->motivo_rejeicao); ?>

                                                    <p class="mt-1">Por favor, substitua o arquivo com as correções solicitadas.</p>
                                                </div>
                                            <?php elseif($documentoEnviado->status === 'enviado'): ?>
                                                <p class="text-xs text-blue-700 mt-1 italic">Aguardando análise pela comissão.</p>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <span class="text-xs font-medium capitalize px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                                Status: Pendente
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    
                                    <div class="w-full sm:w-auto">
                                        <?php if($documentoEnviado): ?>
                                            <div x-data="{ showUpload: false }" class="flex items-center justify-center sm:justify-end space-x-2 flex-wrap gap-2">
                                                <a href="<?php echo e(route('candidato.documentos.show', $documentoEnviado->id)); ?>" target="_blank" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700 whitespace-nowrap">
                                                    Visualizar
                                                </a>
                                                <button type="button" @click="showUpload = !showUpload" class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 whitespace-nowrap">Substituir</button>
                                                
                                                <div x-show="showUpload" x-transition class="mt-2 w-full basis-full">
                                                    <form action="<?php echo e(route('candidato.documentos.store')); ?>" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="tipo_documento" value="<?php echo e($tipo); ?>">
                                                        <input type="file" name="documento" class="text-sm text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required/>
                                                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Enviar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <form action="<?php echo e(route('candidato.documentos.store')); ?>" method="POST" enctype="multipart/form-data" class="flex items-center justify-center sm:justify-end space-x-2">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="tipo_documento" value="<?php echo e($tipo); ?>">
                                                <input type="file" name="documento" class="text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required/>
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 whitespace-nowrap">
                                                    Enviar
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/candidato/documentos/index.blade.php ENDPATH**/ ?>