


<?php $__env->startSection('title', $page->title . ' - Portal do Estagiário'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center"><?php echo e($page->title); ?></h1>

        
        <div class="bg-white shadow-lg rounded-lg p-8 md:p-12 mb-10 prose max-w-none">
            <?php echo $page->content; ?> 
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.site', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/public/perguntas-frequentes-faq.blade.php ENDPATH**/ ?>