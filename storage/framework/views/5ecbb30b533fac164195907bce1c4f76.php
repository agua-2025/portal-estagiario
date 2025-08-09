<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Candidatos</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 10px; }
        .footer { width: 100%; text-align: center; position: fixed; bottom: -15mm; font-size: 8px; }
        .pagenum:before { content: counter(page); }
        .report-title { text-align: center; color: #444; border-bottom: 2px solid #444; padding-bottom: 5px; margin-bottom: 10px; font-size: 16px; }
        .filter-summary { border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; border-radius: 5px; background-color: #f9f9f9;}
        .filter-summary h3 { margin-top: 0; font-size: 12px; }
        .filter-summary p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .prefeitura-header { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .prefeitura-header td { border: none; padding: 0; vertical-align: middle; }
    </style>
</head>
<body>

    
<table class="prefeitura-header">
    <tr>
        
        <td style="width: 50%; text-align: left;">
            <?php if(file_exists(public_path('images/logo.png'))): ?>
                <img src="<?php echo e(public_path('images/logo.png')); ?>" alt="Logomarca" style="width: 220px; height: auto;">
            <?php endif; ?>
        </td>

        
        <td style="width: 50%; text-align: right; font-size: 9px; line-height: 1.4;">
            <div style="font-weight: bold; font-size: 11px; margin-bottom: 5px;"><?php echo e($prefeituraInfo['nome']); ?></div>
            
            <?php echo e($prefeituraInfo['endereco']); ?><br>
            Telefone: <?php echo e($prefeituraInfo['telefone']); ?> | CNPJ: <?php echo e($prefeituraInfo['cnpj']); ?><br>
            Email: <?php echo e($prefeituraInfo['email']); ?>

        </td>
    </tr>
</table>

    <div class="footer">
        Gerado em: <?php echo e($dataGeracao); ?> - Página <span class="pagenum"></span>
    </div>

    <main>
        <div class="report-title">Relatório de Candidatos</div>

        <?php if(!empty($appliedFilters)): ?>
            <div class="filter-summary">
                <h3>Filtros Aplicados:</h3>
                <?php $__currentLoopData = $appliedFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p><strong><?php echo e($key); ?>:</strong> <?php echo e($value); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome Completo</th>
                    <th>CPF</th>
                    <th>Curso</th>
                    <th>Instituição</th>
                    <th>Status</th>
                    <th>Pontuação</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $candidatos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($candidato->nome_completo); ?></td>
                        <td><?php echo e($candidato->cpf); ?></td>
                        <td><?php echo e($candidato->curso_nome); ?></td>
                        <td><?php echo e($candidato->instituicao_nome); ?></td>
                        <td><?php echo e($candidato->status); ?></td>
                        <td><?php echo e(number_format($candidato->pontuacao_final, 2, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum candidato encontrado para os filtros selecionados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/candidatos/relatorios/pdf-template.blade.php ENDPATH**/ ?>