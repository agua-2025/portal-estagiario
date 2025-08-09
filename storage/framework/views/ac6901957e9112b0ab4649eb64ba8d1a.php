<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Perfil - <?php echo e($candidato->nome_completo); ?></title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 11px; }
        .footer { width: 100%; text-align: center; position: fixed; bottom: -15mm; font-size: 9px; }
        .pagenum:before { content: counter(page); }
        .section-title { font-size: 14px; font-weight: bold; color: #333; border-bottom: 2px solid #f0f0f0; padding-bottom: 5px; margin-top: 25px; margin-bottom: 10px; }
        .info-grid { width: 100%; border-collapse: collapse; }
        .info-grid td { padding: 5px 0; vertical-align: top; border-bottom: 1px solid #f5f5f5; }
        .info-grid .label { font-weight: bold; width: 180px; color: #555; }
        .main-header { text-align: center; margin-bottom: 25px; }
        .main-header h1 { margin: 0; font-size: 22px; }
        .main-header h3 { margin: 5px 0 0 0; font-size: 14px; font-weight: normal; color: #666; }
        .prefeitura-header { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .prefeitura-header td { border: none; padding: 0; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="footer">Gerado em: <?php echo e($dataGeracao); ?> - Página <span class="pagenum"></span></div>
    
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
            <h1><?php echo e($candidato->nome_completo); ?></h1>
            <h3>Perfil do Candidato</h3>
        </div>

        <div class="section-title">Dados Pessoais</div>
        <table class="info-grid">
            <tr><td class="label">Nome da Mãe:</td><td><?php echo e($candidato->nome_mae ?? 'Não informado'); ?></td></tr>
            <tr><td class="label">Nome do Pai:</td><td><?php echo e($candidato->nome_pai ?? 'Não informado'); ?></td></tr>
            <tr><td class="label">Data de Nascimento:</td><td><?php echo e(optional($candidato->data_nascimento)->format('d/m/Y')); ?></td></tr>
            <tr><td class="label">Sexo:</td><td><?php echo e($candidato->sexo ?? 'Não informado'); ?></td></tr>
            <tr><td class="label">CPF:</td><td><?php echo e($candidato->cpf); ?></td></tr>
            <tr><td class="label">RG:</td><td><?php echo e($candidato->rg); ?> (<?php echo e($candidato->rg_orgao_expedidor); ?>)</td></tr>
            <tr><td class="label">Possui Deficiência?</td><td><?php echo e($candidato->possui_deficiencia ? 'Sim' : 'Não'); ?></td></tr>
        </table>
        
        <div class="section-title">Contato e Endereço</div>
        <table class="info-grid">
            <tr><td class="label">Email:</td><td><?php echo e(optional($candidato->user)->email); ?></td></tr>
            <tr><td class="label">Telefone:</td><td><?php echo e($candidato->telefone); ?></td></tr>
            <tr><td class="label">Endereço:</td><td><?php echo e($candidato->logradouro); ?>, <?php echo e($candidato->numero); ?> - <?php echo e($candidato->bairro); ?></td></tr>
            <tr><td class="label">Cidade/UF:</td><td><?php echo e($candidato->cidade); ?> / <?php echo e($candidato->estado); ?></td></tr>
            <tr><td class="label">CEP:</td><td><?php echo e($candidato->cep); ?></td></tr>
        </table>

        <div class="section-title">Informações Acadêmicas</div>
        <table class="info-grid">
            <tr><td class="label">Instituição de Ensino:</td><td><?php echo e(optional($candidato->instituicao)->nome); ?></td></tr>
            <tr><td class="label">Curso:</td><td><?php echo e(optional($candidato->curso)->nome); ?></td></tr>
            <tr><td class="label">Início do Curso:</td><td><?php echo e(optional($candidato->curso_data_inicio)->format('m/Y')); ?></td></tr>
            <tr><td class="label">Previsão de Conclusão:</td><td><?php echo e(optional($candidato->curso_previsao_conclusao)->format('m/Y')); ?></td></tr>
            <tr><td class="label">Média de Aproveitamento:</td><td><?php echo e(number_format($candidato->media_aproveitamento, 2, ',', '.')); ?></td></tr>
            <tr><td class="label">Semestres Concluídos:</td><td><?php echo e($candidato->semestres_completos); ?></td></tr>
        </table>

        <div class="section-title">Situação Atual</div>
        <table class="info-grid">
            <tr><td class="label">Status:</td><td><?php echo e($candidato->status); ?></td></tr>
            <tr><td class="label">Pontuação Final:</td><td><?php echo e(number_format($candidato->pontuacao_final, 2, ',', '.')); ?></td></tr>
            <tr><td class="label">Observações da Admin:</td><td><?php echo e($candidato->admin_observacao ?? 'Nenhuma.'); ?></td></tr>
        </table>

    </main>
</body>
</html><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/candidatos/perfil-pdf-template.blade.php ENDPATH**/ ?>