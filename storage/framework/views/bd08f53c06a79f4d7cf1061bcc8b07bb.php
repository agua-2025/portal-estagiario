<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Detalhes da Convocação - <?php echo e($candidato->nome_completo); ?></title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 11px; }
        .footer { width: 100%; text-align: center; position: fixed; bottom: -15mm; font-size: 9px; }
        .section-title { font-size: 14px; font-weight: bold; color: #333; border-bottom: 2px solid #f0f0f0; padding-bottom: 5px; margin-top: 25px; margin-bottom: 10px; }
        .info-grid { width: 100%; border-collapse: collapse; }
        .info-grid td { padding: 5px 0; vertical-align: top; border-bottom: 1px solid #f5f5f5; }
        .info-grid .label { font-weight: bold; width: 180px; color: #555; }
        .main-header { text-align: center; margin-bottom: 25px; }
        .main-header h1 { margin: 0; font-size: 22px; }
        .prefeitura-header { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .prefeitura-header td { border: none; padding: 0; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="footer">Gerado em: <?php echo e($dataGeracao); ?></div>
    
    <table class="prefeitura-header">
        <tr>
            <td style="width: 25%; text-align: left;">
                <?php if(file_exists(public_path('images/logo.png'))): ?>
                    <img src="<?php echo e(public_path('images/logo.png')); ?>" alt="Logomarca" style="width: 220px; height: auto;">
                <?php endif; ?>
            </td>
            <td style="width: 75%; text-align: right; font-size: 9px; line-height: 1.4;">
                <div style="font-weight: bold; font-size: 11px; margin-bottom: 5px;"><?php echo e($prefeituraInfo['nome']); ?></div>
                <?php echo e($prefeituraInfo['endereco']); ?><br>
                Telefone: <?php echo e($prefeituraInfo['telefone']); ?> | CNPJ: <?php echo e($prefeituraInfo['cnpj']); ?><br>
                Email: <?php echo e($prefeituraInfo['email']); ?>

            </td>
        </tr>
    </table>

    <main>
        <div class="main-header">
            <h1>Termo de Convocação</h1>
        </div>

        <div class="section-title">Dados do Candidato</div>
        <table class="info-grid">
            <tr><td class="label">Nome Completo:</td><td><?php echo e($candidato->nome_completo); ?></td></tr>
            <tr><td class="label">CPF:</td><td><?php echo e($candidato->cpf); ?></td></tr>
            <tr><td class="label">Curso:</td><td><?php echo e(optional($candidato->curso)->nome); ?></td></tr>
        </table>
        
        <div class="section-title">Dados da Lotação</div>
        <table class="info-grid">
            <tr><td class="label">Local da Vaga (Lotação):</td><td><?php echo e($candidato->lotacao_local ?? 'Não informado'); ?></td></tr>
            <tr><td class="label">Chefia Imediata:</td><td><?php echo e($candidato->lotacao_chefia ?? 'Não informado'); ?></td></tr>
        </table>

        <div class="section-title">Detalhes do Contrato</div>
        <table class="info-grid">
            <tr><td class="label">Data de Início:</td><td><?php echo e(optional($candidato->contrato_data_inicio)->format('d/m/Y')); ?></td></tr>
            <tr><td class="label">Data de Término:</td><td><?php echo e(optional($candidato->contrato_data_fim)->format('d/m/Y')); ?></td></tr>
            <tr><td class="label">Data da Convocação:</td><td><?php echo e(optional($candidato->convocado_em)->format('d/m/Y \à\s H:i')); ?></td></tr>
        </table>
        
        <div class="section-title">Observações</div>
        <p><?php echo e($candidato->lotacao_observacoes ?? 'Nenhuma observação.'); ?></p>

    </main>
</body>
</html><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/candidatos/relatorios/convocacao-pdf-template.blade.php ENDPATH**/ ?>