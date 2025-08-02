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
    <div class="py-8" x-data="{ tab: 'analise', showRejectionModal: false, rejectionAction: '', showScoreDetails: false, showProfileRejectionModal: false, showDocRejectionModal: false, docRejectionAction: '', showResourceDenialModal: false, resourceDenialAction: '', showResourceApprovalModal: false, resourceApprovalAction: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    
                    <div class="flex flex-col lg:flex-row justify-between items-start mb-4 border-b pb-3">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800"><?php echo e($candidato->nome_completo ?? $candidato->user->name); ?></h2>
                            <p class="text-sm text-gray-500">Inscrição: <?php echo e($candidato->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="mt-3 lg:mt-0 flex items-center gap-3">
                            <?php
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = $candidato->status;
                                if ($candidato->status === 'Inscrição Incompleta') {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($candidato->status === 'Em Análise') {
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                } elseif ($candidato->status === 'Aprovado') {
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($candidato->status === 'Homologado') {
                                    $statusClass = 'bg-purple-100 text-purple-800';
                                } elseif ($candidato->status === 'Rejeitado') {
                                    $statusClass = 'bg-red-100 text-red-800';
                                }
                            ?>
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
                            <a href="<?php echo e(route('admin.candidatos.index')); ?>" class="px-3 py-1 bg-gray-200 border border-transparent rounded text-xs text-gray-700 hover:bg-gray-300">Voltar</a>
                        </div>
                    </div>

                    
                    <?php
                        $profile_was_updated = false;
                        $changed_document_types = [];
                        if($candidato->revert_reason && is_array($candidato->revert_reason) && !empty($candidato->revert_reason)) {
                            $history = $candidato->revert_reason;
                            $changes_to_review = [];
                            $reversed_history = array_reverse($history);
                            foreach ($reversed_history as $event) {
                                $changes_to_review[] = $event;
                                if (in_array($event['previous_status'], ['Homologado', 'Aprovado'])) {
                                    break;
                                }
                            }
                            foreach ($changes_to_review as $change) {
                                if ($change['action'] === 'profile_update') {
                                    $profile_was_updated = true;
                                }
                                if (in_array($change['action'], ['document_update', 'document_delete'])) {
                                    if (!in_array($change['document_type'], $changed_document_types)) {
                                        $changed_document_types[] = $change['document_type'];
                                    }
                                }
                            }
                        }
                        $prazosAtivos = $candidato->atividades()->where('status', 'Rejeitada')->where('prazo_recurso_ate', '>', now())->exists();
                    ?>

                    
                    <?php if($profile_was_updated): ?>
                        <div class="mb-4 p-3 border-l-4 border-yellow-500 bg-yellow-50 text-yellow-800 rounded text-sm" role="alert">
                            <p><span class="font-semibold">Atenção:</span> Informações do perfil alteradas - requer reanálise.</p>
                        </div>
                    <?php endif; ?>

                    
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-blue-800">Pontuação Total (Itens Aprovados)</h3>
                                <p class="mt-1 text-2xl font-bold text-blue-900"><?php echo e(number_format($pontuacaoTotal, 2, ',', '.')); ?> pontos</p>
                            </div>
                            <button @click="showScoreDetails = !showScoreDetails" class="text-xs text-blue-600 hover:underline px-2 py-1">
                                <span x-show="!showScoreDetails">Ver Detalhes</span>
                                <span x-show="showScoreDetails">Ocultar</span>
                            </button>
                        </div>
                        <div x-show="showScoreDetails" x-transition class="mt-3 pt-3 border-t border-blue-200">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase mb-2">Extrato de Pontos</h4>
                            <table class="min-w-full text-xs">
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $detalhesPontuacao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalhe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b border-blue-100 last:border-b-0">
                                        <td class="py-1 pr-4 text-gray-700"><?php echo e($detalhe['nome']); ?></td>
                                        <td class="py-1 pl-4 text-right font-medium text-gray-900"><?php echo e(number_format($detalhe['pontos'], 2, ',', '.')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td class="py-1 text-gray-500">Nenhuma atividade aprovada.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                            <button @click="tab = 'perfil'" :class="{ 'border-blue-500 text-blue-600': tab === 'perfil', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'perfil' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Perfil</button>
                            <button @click="tab = 'analise'" :class="{ 'border-blue-500 text-blue-600': tab === 'analise', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'analise' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Documentos</button>
                            <button @click="tab = 'acoes'" :class="{ 'border-blue-500 text-blue-600': tab === 'acoes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'acoes' }" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Ações Finais</button>
                        </nav>
                    </div>

                    
                    <div x-show="tab === 'perfil'" x-transition>
                        <?php
                        function renderDetail($label, $value) {
                            if (empty($value) && !is_numeric($value)) return;
                            echo '<div class="mb-3"><h4 class="text-xs font-medium text-gray-500 uppercase">' . $label . '</h4><p class="mt-1 text-sm text-gray-900">' . e($value) . '</p></div>';
                        }
                        ?>
                        <div class="mt-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Dados Pessoais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                                <?php echo e(renderDetail('Nome da Mãe', $candidato->nome_mae)); ?>

                                <?php echo e(renderDetail('Nome do Pai', $candidato->nome_pai)); ?>

                                <?php echo e(renderDetail('Data de Nascimento', optional($candidato->data_nascimento)->format('d/m/Y'))); ?>

                                <?php echo e(renderDetail('Sexo', $candidato->sexo)); ?>

                                <?php echo e(renderDetail('CPF', $candidato->cpf)); ?>

                                <?php echo e(renderDetail('RG', $candidato->rg)); ?>

                                <?php echo e(renderDetail('Órgão Expedidor', $candidato->rg_orgao_expedidor)); ?>

                                <?php echo e(renderDetail('Telefone', $candidato->telefone)); ?>

                                <?php echo e(renderDetail('Possui Deficiência?', $candidato->possui_deficiencia ? 'Sim' : 'Não')); ?>

                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Dados Acadêmicos</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                               <?php echo e(renderDetail('Instituição de Ensino', $candidato->instituicao->nome ?? 'N/A')); ?>

                                <?php echo e(renderDetail('Curso', $candidato->curso->nome ?? 'N/A')); ?>

                                <?php echo e(renderDetail('Início do Curso', optional($candidato->curso_data_inicio)->format('d/m/Y'))); ?>

                                <?php echo e(renderDetail('Previsão de Conclusão', optional($candidato->curso_previsao_conclusao)->format('d/m/Y'))); ?>

                                <?php echo e(renderDetail('Média de Aproveitamento', $candidato->media_aproveitamento)); ?>

                                <?php echo e(renderDetail('Semestres Concluídos', $candidato->semestres_completos)); ?>

                            </div>
                        </div>
                    </div>

                    
                    <div x-show="tab === 'analise'" x-transition style="display: none;">
                        
                        
                        <div class="mb-6">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Documentos Obrigatórios</h3>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $documentosNecessarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipoDocumento => $nomeDocumento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $documentoEnviado = $documentosEnviados->get($tipoDocumento); ?>
                                    <div class="p-3 border rounded bg-gray-50 text-sm">
                                        <div class="flex justify-between items-center">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="font-medium truncate"><?php echo e($nomeDocumento); ?></p>
                                                    <?php if(in_array($tipoDocumento, $changed_document_types) && $documentoEnviado && $documentoEnviado->status !== 'aprovado'): ?>
                                                        <span class="px-2 py-0.5 bg-yellow-200 text-yellow-800 rounded-full text-xs font-medium flex-shrink-0">ALTERADO</span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if($documentoEnviado): ?>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                                            <?php if($documentoEnviado->status == 'aprovado'): ?> bg-green-100 text-green-800 <?php endif; ?>
                                                            <?php if($documentoEnviado->status == 'enviado'): ?> bg-blue-100 text-blue-800 <?php endif; ?>
                                                            <?php if($documentoEnviado->status == 'rejeitado'): ?> bg-red-100 text-red-800 <?php endif; ?>
                                                        "><?php echo e(ucfirst($documentoEnviado->status)); ?></span>
                                                    </div>
                                                    <?php if($documentoEnviado->status == 'rejeitado' && $documentoEnviado->motivo_rejeicao): ?>
                                                        <p class="text-xs text-red-700 mt-1"><?php echo e($documentoEnviado->motivo_rejeicao); ?></p>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <<div class="flex items-center gap-1 ml-3 flex-shrink-0">
    <?php if($documentoEnviado): ?>
        
        <a href="<?php echo e(route('candidato.documentos.show', $documentoEnviado)); ?>" target="_blank" class="px-3 py-1 bg-gray-600 text-white rounded text-xs hover:bg-gray-700">Ver</a>

        
        <?php if($candidato->status !== 'Convocado'): ?>

            <?php if($documentoEnviado->status === 'rejeitado'): ?>
                <button type="button" disabled onclick="alert('Candidato deve reenviar documento corrigido.')" class="py-1 bg-gray-400 text-white rounded text-xs cursor-not-allowed w-[70px] flex justify-center items-center">Bloqueado</button>
            <?php elseif($documentoEnviado->status !== 'aprovado'): ?>
                <form action="<?php echo e(route('admin.documentos.updateStatus', $documentoEnviado)); ?>" method="POST" onsubmit="return confirm('Aprovar documento?');" class="inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="status" value="aprovado">
                    <button type="submit" class="py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 w-[70px] flex justify-center items-center">Aprovar</button>
                </form>
            <?php endif; ?>
            
            <?php if($documentoEnviado->status !== 'rejeitado'): ?>
                <button @click="if(confirm('Tem certeza que deseja rejeitar? Esta ação só poderá ser revista após o reenvio de um novo documento pelo candidato.')) { showDocRejectionModal = true; docRejectionAction = '<?php echo e(route('admin.documentos.updateStatus', $documentoEnviado)); ?>' }" type="button" class="py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 w-[70px] flex justify-center items-center">Rejeitar</button>
            <?php endif; ?>

        <?php else: ?>
            
            <span class="px-3 py-1 bg-gray-700 text-white rounded text-xs w-[70px] cursor-not-allowed flex justify-center items-center" title="Ações bloqueadas pois o candidato já foi convocado.">Convocado</span>
        <?php endif; ?>
    <?php else: ?>
        <span class="text-xs text-gray-500">Aguardando envio</span>
    <?php endif; ?>
</div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
<div class="mb-6">
    <h3 class="text-base font-semibold text-gray-800 mb-3">Atividades Complementares</h3>
    <div class="space-y-2">
        <?php $__currentLoopData = $candidato->atividades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atividade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-3 border rounded bg-gray-50 text-sm">
                <div class="flex justify-between items-start">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <?php
                                $statusClassAtividade = 'bg-yellow-100 text-yellow-800';
                                if ($atividade->status === 'Aprovada') $statusClassAtividade = 'bg-green-100 text-green-800';
                                elseif ($atividade->status === 'Rejeitada') $statusClassAtividade = 'bg-red-100 text-red-800';
                                elseif ($atividade->status === 'enviado') $statusClassAtividade = 'bg-blue-100 text-blue-800';
                                elseif ($atividade->status === 'Em Análise') $statusClassAtividade = 'bg-purple-100 text-purple-800';
                            ?>
                            <span class="font-medium px-2 py-0.5 rounded-full text-xs <?php echo e($statusClassAtividade); ?>"><?php echo e($atividade->status); ?></span>
                            <p class="font-medium truncate"><?php echo e($atividade->tipoDeAtividade->nome ?? 'Regra não encontrada'); ?></p>
                        </div>
                        <p class="text-xs text-gray-600 mb-2"><?php echo e($atividade->descricao_customizada); ?></p>
                        
                        <div class="text-xs text-gray-700 pl-2 border-l-2 border-gray-200">
                            <?php if(str_contains(strtolower($atividade->tipoDeAtividade->nome), 'aproveitamento acadêmico')): ?>
                                <p><strong>Média:</strong> <?php echo e($candidato->media_aproveitamento ?? 'N/A'); ?></p>
                            <?php elseif($atividade->tipoDeAtividade->unidade_medida === 'horas'): ?>
                                <p><strong>Horas:</strong> <?php echo e($atividade->carga_horaria ?? 'N/A'); ?></p>
                            <?php elseif($atividade->tipoDeAtividade->unidade_medida === 'meses'): ?>
                                <p><strong>Período:</strong> <?php echo e(optional($atividade->data_inicio)->format('d/m/Y') ?? 'N/A'); ?> a <?php echo e(optional($atividade->data_fim)->format('d/m/Y') ?? 'N/A'); ?></p>
                            <?php elseif(str_contains(strtolower($atividade->tipoDeAtividade->nome), 'semestres cursados') || $atividade->tipoDeAtividade->unidade_medida === 'semestre'): ?>
                                <p><strong>Semestres:</strong> <?php echo e($atividade->semestres_declarados ?? 'N/A'); ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if($atividade->status === 'Rejeitada' && $atividade->motivo_rejeicao): ?>
                            <div class="text-xs text-red-700 mt-2 p-2 bg-red-50 rounded">
                                <p class="break-words"><strong>Motivo:</strong> <?php echo e($atividade->motivo_rejeicao); ?></p>
                                <?php if($atividade->prazo_recurso_ate): ?>
                                    <?php if(\Carbon\Carbon::now()->lt($atividade->prazo_recurso_ate)): ?>
                                        <p class="mt-1 text-blue-700"><strong>Prazo para Recurso:</strong> até <?php echo e(\Carbon\Carbon::parse($atividade->prazo_recurso_ate)->format('d/m/Y')); ?> às 17h00 (2 dias úteis)</p>
                                    <?php else: ?>
                                        <p class="mt-1 text-gray-600"><strong>Prazo Encerrado</strong></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="flex items-center gap-1 ml-3 flex-shrink-0">
                        <a href="<?php echo e(route('candidato.atividades.show', $atividade)); ?>" target="_blank" class="px-3 py-1 bg-gray-600 text-white rounded text-xs hover:bg-gray-700">Ver</a>

                        
                        <?php if($candidato->status !== 'Convocado'): ?>
                            <?php
                                $prazoExpirado = false;
                                if ($atividade->status === 'Rejeitada' && $atividade->prazo_recurso_ate && \Carbon\Carbon::now()->gt($atividade->prazo_recurso_ate)) {
                                    $prazoExpirado = true;
                                }
                            ?>
                            
                            
                            <?php if(!$prazoExpirado): ?>
                                <?php if($atividade->status !== 'Aprovada'): ?>
                                    <form action="<?php echo e(route('admin.atividades.aprovar', $atividade->id)); ?>" method="POST" onsubmit="return confirm('Aprovar item?');" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 w-[70px] flex justify-center items-center">Aprovar</button>
                                    </form>
                                <?php endif; ?>
                                <?php if($atividade->status !== 'Rejeitada'): ?>
                                    <button @click="showRejectionModal = true; rejectionAction = '<?php echo e(route('admin.atividades.rejeitar', $atividade->id)); ?>'" type="button" class="py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700 w-[70px] flex justify-center items-center">Rejeitar</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-gray-400 text-white rounded text-xs w-[70px] cursor-not-allowed flex justify-center items-center" title="O prazo para o candidato recorrer já encerrou.">Bloqueado</span>
                            <?php endif; ?>
                        <?php else: ?>
                            
                            <span class="px-3 py-1 bg-gray-700 text-white rounded text-xs w-[70px] cursor-not-allowed flex justify-center items-center" title="Ações bloqueadas pois o candidato já foi convocado.">Convocado</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
</div>

                    
                    <div x-show="tab === 'acoes'" x-transition x-cloak>
                        <div class="mt-4 p-4 bg-gray-50 rounded">
                           <h3 class="text-base font-semibold text-gray-800 mb-3">Painel de Ações</h3>
                           
                           <div class="mt-4">
                               <h4 class="text-sm font-bold text-gray-800 mb-3">Histórico de Recursos</h4>
                               <?php if($candidato->recurso_historico && count($candidato->recurso_historico) > 0): ?>
                                   <div class="space-y-4">
                                       <?php $__currentLoopData = $candidato->recurso_historico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $recurso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <div class="p-4 border rounded 
                                               <?php if(empty($recurso['decisao_admin'])): ?> bg-purple-50 border-purple-200 
                                               <?php elseif(strtolower($recurso['decisao_admin']) === 'deferido'): ?> bg-green-50 border-green-200
                                               <?php else: ?> bg-red-50 border-red-200 <?php endif; ?>">
                                               
                                               <div class="flex justify-between items-center pb-2 border-b 
                                                   <?php if(empty($recurso['decisao_admin'])): ?> border-purple-200
                                                   <?php elseif(strtolower($recurso['decisao_admin']) === 'deferido'): ?> border-green-200
                                                   <?php else: ?> border-red-200 <?php endif; ?>">
                                                   <h5 class="font-medium text-sm text-gray-900">
                                                       Recurso #<?php echo e(count($candidato->recurso_historico) - $index); ?>

                                                       <span class="ml-2 text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($recurso['data_envio'])->format('d/m/Y H:i')); ?></span>
                                                   </h5>
                                                   <?php if(!empty($recurso['decisao_admin'])): ?>
                                                       <span class="px-2 py-1 text-xs font-medium rounded-full
                                                           <?php if(strtolower($recurso['decisao_admin']) === 'deferido'): ?> bg-green-200 text-green-800 
                                                           <?php else: ?> bg-red-200 text-red-800 <?php endif; ?>"><?php echo e(ucfirst($recurso['decisao_admin'])); ?></span>
                                                   <?php else: ?>
                                                       <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-200 text-yellow-800">Em Análise</span>
                                                   <?php endif; ?>
                                               </div>
                                               
                                               <div class="mt-2">
                                                   <p class="text-xs font-medium text-gray-700">Argumento:</p>
                                                   <div class="mt-1 text-xs text-gray-800 bg-white p-2 rounded border whitespace-pre-wrap break-all"><?php echo e($recurso['argumento_candidato']); ?></div>
                                               </div>

                                               <?php if(!empty($recurso['decisao_admin'])): ?>
                                                   <div class="mt-2">
                                                       <p class="text-xs font-medium text-gray-700">Justificativa:</p>
                                                       <div class="mt-1 text-xs text-gray-800 bg-white p-2 rounded border whitespace-pre-wrap break-all"><?php echo e($recurso['justificativa_admin'] ?? 'Não fornecida.'); ?></div>


                                                   </div>
                                               <?php else: ?>
                                                   <div class="mt-3 pt-2 border-t border-purple-200">
                                                       <p class="text-xs font-medium text-gray-800 mb-2">Decisão:</p>
                                                       <div class="flex gap-2">
                                                            <button @click="showResourceApprovalModal = true; resourceApprovalAction = '<?php echo e(route('admin.recursos.deferir', ['candidato' => $candidato, 'recurso_index' => $index])); ?>'" type="button" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Deferir</button>
                                                            <button @click="showResourceDenialModal = true; resourceDenialAction = '<?php echo e(route('admin.recursos.indeferir', ['candidato' => $candidato, 'recurso_index' => $index])); ?>'" type="button" class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Indeferir</button>
                                                       </div>
                                                   </div>
                                               <?php endif; ?>
                                           </div>
                                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   </div>
                               <?php else: ?>
                                   <div class="text-center py-4 bg-gray-50 rounded border">
                                       <p class="text-xs text-gray-500">Nenhum recurso interposto.</p>
                                   </div>
                               <?php endif; ?>
                           </div>

                           <?php
                                $recursoEmAnalise = false;
                                if ($candidato->recurso_historico) {
                                    foreach ($candidato->recurso_historico as $recurso) {
                                        if (empty($recurso['decisao_admin'])) {
                                            $recursoEmAnalise = true;
                                            break;
                                        }
                                    }
                                }
                           ?>

                           <?php if(!$recursoEmAnalise): ?>
                               <div class="mt-6 pt-4 border-t border-gray-300">
                                    <p class="text-xs text-gray-600 mb-3">Ações finais da inscrição:</p>
                                   
                                   <?php if($prazosAtivos): ?>
                                       <div class="p-3 bg-yellow-100 text-yellow-800 rounded">
                                           <p class="font-medium text-sm">Ações Bloqueadas</p>
                                           <p class="text-xs">Aguarde término dos prazos de recurso.</p>
                                       </div>
                                   <?php else: ?>
                                       <?php if($candidato->status === 'Em Análise'): ?>
                                           <form action="<?php echo e(route('admin.candidatos.update', $candidato->id)); ?>" method="POST" onsubmit="return confirm('Alterar status da inscrição?');">
                                               <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                               <div class="space-y-3">
                                                   <div>
                                                       <label for="admin_observacao" class="block text-xs font-medium text-gray-700">Justificativa</label>
                                                       <textarea name="admin_observacao" id="admin_observacao" rows="2" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-xs"><?php echo e($candidato->admin_observacao); ?></textarea>
                                                   </div>
                                                   <div class="flex gap-2">
                                                       <button type="submit" name="status" value="Aprovado" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Aprovar</button>
                                                       <button @click="showProfileRejectionModal = true" type="button" class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Rejeitar</button>
                                                   </div>
                                               </div>
                                           </form>
                                       <?php elseif($candidato->status === 'Aprovado'): ?>
                                           <form action="<?php echo e(route('admin.candidatos.homologar', $candidato->id)); ?>" method="POST" class="w-full">
                                               <?php echo csrf_field(); ?>
                                               <div class="bg-yellow-50 p-3 rounded">
                                                   <p class="font-medium text-yellow-800 mb-2 text-sm">Homologar Candidato</p>
                                                   <div class="mb-2">
                                                       <label for="ato_homologacao" class="block text-xs font-medium text-gray-700">Nº do Ato <span class="text-red-500">*</span></label>
                                                       <input type="text" name="ato_homologacao" id="ato_homologacao" required class="mt-1 block w-full rounded border-gray-300 shadow-sm text-xs" value="<?php echo e(old('ato_homologacao', $candidato->ato_homologacao)); ?>">
                                                       <?php $__errorArgs = ['ato_homologacao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                   </div>
                                                   <div class="mb-2">
                                                       <label for="homologacao_observacoes" class="block text-xs font-medium text-gray-700">Observações</label>
                                                       <textarea name="homologacao_observacoes" id="homologacao_observacoes" rows="2" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-xs"><?php echo e(old('homologacao_observacoes', $candidato->homologacao_observacoes)); ?></textarea>
                                                       <?php $__errorArgs = ['homologacao_observacoes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                   </div>
                                                   <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded text-xs hover:bg-purple-700">Homologar</button>
                                               </div>
                                           </form>
                                       <?php elseif($candidato->status === 'Homologado'): ?>
                                           <div class="bg-blue-50 p-3 rounded">
                                               <p class="font-medium text-blue-800 mb-1 text-sm">Candidato Homologado</p>
                                               <p class="text-xs text-blue-700">Ato: <?php echo e($candidato->ato_homologacao ?? 'N/A'); ?></p>
                                               <p class="text-xs text-blue-700">Data: <?php echo e($candidato->homologado_em ? $candidato->homologado_em->format('d/m/Y H:i') : 'N/A'); ?></p>
                                               <?php if($candidato->homologacao_observacoes): ?>
                                                   <p class="text-xs text-blue-700 mt-1">Obs: <?php echo e($candidato->homologacao_observacoes); ?></p>
                                               <?php endif; ?>
                                           </div>
                                       <?php else: ?>
                                           <div class="bg-gray-50 p-3 rounded">
                                               <p class="font-medium text-gray-800 mb-1 text-sm">Status Atual</p>
                                               <p class="text-xs text-gray-700">Sem ações disponíveis no momento.</p>
                                           </div>
                                       <?php endif; ?>
                                   <?php endif; ?>
                               </div>
                           <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="showRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showRejectionModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Justificar Rejeição</h3>
                            <form :action="rejectionAction" method="POST">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label for="motivo_rejeicao" class="block text-sm font-medium text-gray-700">Motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="motivo_rejeicao" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showRejectionModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="showProfileRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showProfileRejectionModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Rejeitar Inscrição</h3>
                            <form action="<?php echo e(route('admin.candidatos.update', $candidato->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="Rejeitado">
                                <div>
                                    <label for="profile_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Motivo da rejeição:</label>
                                    <textarea name="admin_observacao" id="profile_motivo_rejeicao" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showProfileRejectionModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="showDocRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showDocRejectionModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Rejeitar Documento</h3>
                            <form :action="docRejectionAction" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="rejeitado">
                                <div>
                                    <label for="doc_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="doc_motivo_rejeicao" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showDocRejectionModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Confirmar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div x-show="showResourceDenialModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceDenialModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Indeferir Recurso</h3>
                            <form :action="resourceDenialAction" method="POST">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label for="indeferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa (visível ao candidato):</label>
                                    <textarea name="justificativa_admin" id="indeferimento_motivo" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" required minlength="10" placeholder="Ex: Argumentos não alteram decisão inicial..."></textarea>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showResourceDenialModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Indeferir</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div x-show="showResourceApprovalModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceApprovalModal = false" class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-3">Deferir Recurso</h3>
                             <form :action="resourceApprovalAction" method="POST">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label for="deferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa (Opcional):</label>
                                    <textarea name="justificativa_admin" id="deferimento_motivo" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm text-sm" placeholder="Ex: Após reanálise, pontuação foi ajustada."></textarea>
                                </div>
                                <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
                                    <strong>Atenção:</strong> Você deve reanalisar manualmente as atividades e documentos.
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="showResourceApprovalModal = false" type="button" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Deferir</button>
                                </div>
                            </form>
                        </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/candidatos/show.blade.php ENDPATH**/ ?>