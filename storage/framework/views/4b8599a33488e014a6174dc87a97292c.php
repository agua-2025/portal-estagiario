<?php $__env->startSection('title', 'Fale Conosco - Portal do Estagiário'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center">Fale Conosco</h1>
        
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8 md:p-12 mb-10">
            
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Sucesso!</strong>
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Ops!</strong>
                    <span class="block sm:inline">Por favor, corrija os seguintes erros:</span>
                    <ul class="mt-3 list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('contato.send')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?> 

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Seu Nome</label>
                    <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Seu E-mail</label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">Assunto</label>
                    <input type="text" name="subject" id="subject" value="<?php echo e(old('subject')); ?>" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Mensagem</label>
                    <textarea name="message" id="message" rows="5" required
                              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?php echo e(old('message')); ?></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/public/contato.blade.php ENDPATH**/ ?>