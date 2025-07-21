<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Portal do Estagiário</title>
    <!-- Tailwind CSS CDN (para simplificar, em um projeto real você usaria o build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Você pode adicionar seus próprios estilos CSS aqui -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen"> 
    <div class="w-full max-w-4xl mx-auto p-6"> 
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erro!</strong>
                <span class="block sm:inline"><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?> 
    </div>
</body>
</html><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/layouts/admin.blade.php ENDPATH**/ ?>