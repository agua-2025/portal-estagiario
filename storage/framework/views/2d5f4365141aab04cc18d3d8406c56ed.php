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
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Meus Documentos</h2>
                        <p class="mt-1 text-sm text-gray-600">Envie os documentos necessários para validar a sua inscrição.</p>
                    </div>
                    
                    
                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg" role="alert">
                            <p><?php echo e(session('success')); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($candidato->status === 'Inscrição Incompleta' && !empty($candidato->admin_observacao)): ?>
                        <div class="p-4 mb-6 border-l-4 border-red-500 bg-red-50 text-red-800 rounded-r-lg" role="alert">
                            <h3 class="font-bold">Correção Necessária!</h3>
                            <p class="mt-1 text-sm">A Comissão Organizadora solicitou uma correção no seu cadastro. O andamento do seu processo seletivo permanecerá suspenso até que o ajuste seja devidamente realizado.</p>
                        </div>
                    <?php endif; ?>


                    
                    <div class="space-y-4">
                        <?php $__currentLoopData = $documentosNecessarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $documentoEnviado = $documentosEnviados->get($tipo);
                            ?>

                            
                            <div class="py-4 border-b last:border-b-0">
                                <div class="flex justify-between items-start gap-4">
                                    
                                    <div class="flex-grow">
                                        <p class="font-semibold text-gray-800"><?php echo e($nome); ?></p>

                                        <?php if($documentoEnviado && $documentoEnviado->status === 'rejeitado' && !empty($documentoEnviado->motivo_rejeicao)): ?>
                                            
                                            <div class="mt-2 p-2 text-xs text-red-800 bg-red-50 rounded-md border border-red-200 break-all">
                                                <strong class="font-bold">Motivo da Rejeição:</strong> <?php echo e($documentoEnviado->motivo_rejeicao); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div class="flex items-center flex-shrink-0 gap-x-2">
                                        
                                        <?php if($documentoEnviado): ?>
                                            <span class="font-semibold capitalize px-3 py-1.5 rounded-md text-xs
                                                <?php if($documentoEnviado->status == 'aprovado'): ?> bg-green-100 text-green-800 <?php endif; ?>
                                                <?php if($documentoEnviado->status == 'enviado' || $documentoEnviado->status == 'Em Análise'): ?> bg-purple-100 text-purple-800 <?php endif; ?>
                                                <?php if($documentoEnviado->status == 'rejeitado'): ?> bg-red-100 text-red-800 <?php endif; ?>
                                            "><?php echo e($documentoEnviado->status); ?></span>
                                        <?php else: ?>
                                            <span class="font-semibold capitalize px-3 py-1.5 rounded-md text-xs bg-yellow-100 text-yellow-800">Pendente</span>
                                        <?php endif; ?>
                                        
                                        
                                        <?php if($documentoEnviado): ?>
                                             <a href="<?php echo e(route('candidato.documentos.show', $documentoEnviado->id)); ?>" target="_blank" class="px-3 py-1.5 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold hover:bg-gray-300">Visualizar</a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div class="mt-3 pl-4">
                                    <?php if(!$documentoEnviado): ?>
                                        <form action="<?php echo e(route('candidato.documentos.store')); ?>" method="POST" enctype="multipart/form-data" class="flex items-center gap-x-3">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="tipo_documento" value="<?php echo e($tipo); ?>">
                                            <input type="file" name="documento" required class="text-sm text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                            <button type="submit" class="px-4 py-1.5 border border-blue-600 text-blue-700 rounded-md text-sm font-semibold hover:bg-blue-600 hover:text-white ml-auto transition-colors duration-200">Enviar Correção</button>
                                        </form>

<?php elseif($documentoEnviado->status === 'rejeitado'): ?>
    <form action="<?php echo e(route('candidato.documentos.store')); ?>" method="POST" enctype="multipart/form-data" class="flex items-center gap-x-3">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="tipo_documento" value="<?php echo e($tipo); ?>">
        <span class="text-sm text-gray-600">Substituir arquivo:</span>
        <input type="file" name="documento" required class="text-sm text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
        
        
        <button type="submit" class="px-4 py-1.5 border border-blue-600 text-blue-700 rounded-md text-sm font-semibold hover:bg-blue-600 hover:text-white ml-auto transition-colors duration-200">
            Enviar Correção
        </button>
    </form>
<?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/candidato/documentos/index.blade.php ENDPATH**/ ?>