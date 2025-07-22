 

<?php $__env->startSection('title', 'Criar Nova Página'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Criar Nova Página</h1>

        
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

        <div class="bg-white shadow-md rounded-lg p-8">
            <form action="<?php echo e(route('admin.pages.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?> 

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Título da Página</label>
                    <input type="text" name="title" id="title" value="<?php echo e(old('title')); ?>" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL amigável)</label>
                    <input type="text" name="slug" id="slug" value="<?php echo e(old('slug')); ?>"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Deixe em branco para gerar automaticamente do título">
                    <p class="mt-2 text-xs text-gray-500">Ex: politica-de-privacidade, termos-de-uso</p>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Conteúdo da Página (HTML)</label>
                    <textarea name="content" id="content" rows="15" required
                              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?php echo e(old('content')); ?></textarea>
                    <p class="mt-2 text-xs text-gray-500">Você pode inserir conteúdo HTML aqui. Considere usar um editor WYSIWYG para melhor experiência.</p>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="<?php echo e(route('admin.pages.index')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Salvar Página
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/pages/create.blade.php ENDPATH**/ ?>