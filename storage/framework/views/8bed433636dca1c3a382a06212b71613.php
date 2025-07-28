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
    
    <div class="py-12" x-data="{ tab: 'acoes', showRejectionModal: false, rejectionAction: '', showScoreDetails: false, showProfileRejectionModal: false, showDocRejectionModal: false, docRejectionAction: '', showResourceDenialModal: false, resourceDenialAction: '', showResourceApprovalModal: false, resourceApprovalAction: '' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800"><?php echo e($candidato->nome_completo ?? $candidato->user->name); ?></h2>
                            <p class="text-sm text-gray-500">Inscrição recebida em: <?php echo e($candidato->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-center gap-4">
                            <?php
                                $statusClass = 'bg-gray-100 text-gray-800'; // Default
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
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?php echo e($statusClass); ?>">
                                <?php echo e($statusText); ?>

                            </span>
                            <a href="<?php echo e(route('admin.candidatos.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                Voltar
                            </a>
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

                        $prazosAtivos = $candidato->atividades()
                                                ->where('status', 'Rejeitada')
                                                ->where('prazo_recurso_ate', '>', now())
                                                ->exists();
                    ?>

                    
                    <?php if($profile_was_updated): ?>
                        <div class="mb-6 p-4 border-l-4 border-yellow-500 bg-yellow-100 text-yellow-800 rounded-lg text-sm" role="alert">
                            <p><span class="font-bold">Atenção:</span> As informações do perfil do candidato foram alteradas recentemente e precisam de reanálise.</p>
                        </div>
                    <?php endif; ?>

                    
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-medium text-blue-800">Pontuação Total (Itens Aprovados)</h3>
                                <p class="mt-1 text-3xl font-bold text-blue-900"><?php echo e(number_format($pontuacaoTotal, 2, ',', '.')); ?> pontos</p>
                            </div>
                            <button @click="showScoreDetails = !showScoreDetails" class="text-sm text-blue-600 hover:underline">
                                <span x-show="!showScoreDetails">Ver Detalhes</span>
                                <span x-show="showScoreDetails">Esconder Detalhes</span>
                            </button>
                        </div>
                        
                        <div x-show="showScoreDetails" x-transition class="mt-4 pt-4 border-t border-blue-200">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase mb-2">Extrato de Pontos</h4>
                            <table class="min-w-full text-sm">
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $detalhesPontuacao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalhe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b border-blue-100 last:border-b-0">
                                        <td class="py-2 pr-4 text-gray-700"><?php echo e($detalhe['nome']); ?></td>
                                        <td class="py-2 pl-4 text-right font-medium text-gray-900"><?php echo e(number_format($detalhe['pontos'], 2, ',', '.')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="py-2 text-gray-500">Nenhuma atividade foi aprovada ainda.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                            <button @click="tab = 'perfil'" :class="{ 'border-blue-500 text-blue-600': tab === 'perfil', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'perfil' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Perfil do Candidato
                            </button>
                            <button @click="tab = 'analise'" :class="{ 'border-blue-500 text-blue-600': tab === 'analise', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'analise' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Análise de Documentos
                            </button>
                             <button @click="tab = 'acoes'" :class="{ 'border-blue-500 text-blue-600': tab === 'acoes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'acoes' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Ações Finais
                            </button>
                        </nav>
                    </div>

                    
                    
                    
                    <div x-show="tab === 'perfil'" x-transition>
                        <?php
                        function renderDetail($label, $value) {
                            if (empty($value) && !is_numeric($value)) return;
                            echo '<div class="mb-4"><h4 class="text-sm font-medium text-gray-500">' . $label . '</h4><p class="mt-1 text-md text-gray-900">' . e($value) . '</p></div>';
                        }
                        ?>
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados Pessoais</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6">
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
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados Acadêmicos</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6">
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
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Documentos e Atividades Enviadas</h3>
                            
                            <?php $__currentLoopData = $documentosNecessarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipoDocumento => $nomeDocumento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $documentoEnviado = $documentosEnviados->get($tipoDocumento);
                                ?>
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start gap-4 text-sm bg-gray-50">
                                    <div class="flex-grow">
                                        <div class="flex items-center">
                                            <p class="font-semibold"><?php echo e($nomeDocumento); ?></p>
                                            <?php if(in_array($tipoDocumento, $changed_document_types) && $documentoEnviado && $documentoEnviado->status !== 'aprovado'): ?>
                                                <span class="ml-3 px-2 py-0.5 bg-yellow-200 text-yellow-800 rounded-full text-xs font-bold">ALTERADO</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($documentoEnviado): ?>
                                            <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full
                                                <?php if($documentoEnviado->status == 'aprovado'): ?> bg-green-100 text-green-800 <?php endif; ?>
                                                <?php if($documentoEnviado->status == 'enviado'): ?> bg-blue-100 text-blue-800 <?php endif; ?>
                                                <?php if($documentoEnviado->status == 'rejeitado'): ?> bg-red-100 text-red-800 <?php endif; ?>
                                            ">
                                                Status: <?php echo e($documentoEnviado->status); ?>

                                            </span>
                                            <?php if($documentoEnviado->status == 'rejeitado' && $documentoEnviado->motivo_rejeicao): ?>
                                                <p class="text-xs text-red-700 mt-1">Motivo: <?php echo e($documentoEnviado->motivo_rejeicao); ?></p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs font-medium capitalize px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Status: Pendente</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 flex-shrink-0">
                                        <?php if($documentoEnviado): ?>
                                            <a href="<?php echo e(route('candidato.documentos.show', $documentoEnviado)); ?>" target="_blank" class="px-3 py-1.5 bg-gray-600 text-white rounded-md text-xs hover:bg-gray-700">Visualizar</a>
                                            
                                            <?php if($documentoEnviado->status !== 'aprovado'): ?>
                                                <form action="<?php echo e(route('admin.documentos.updateStatus', $documentoEnviado)); ?>" method="POST" onsubmit="return confirm('Aprovar este documento?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <input type="hidden" name="status" value="aprovado">
                                                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs hover:bg-green-700">Aprovar</button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if($documentoEnviado->status !== 'rejeitado'): ?>
                                                <button @click="showDocRejectionModal = true; docRejectionAction = '<?php echo e(route('admin.documentos.updateStatus', $documentoEnviado)); ?>'" type="button" class="px-3 py-1.5 bg-red-600 text-white rounded-md text-xs hover:bg-red-700">Rejeitar</button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Aguardando envio do candidato.</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php $__currentLoopData = $candidato->atividades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atividade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm bg-gray-50">
                                    <div class="flex-grow mb-3 sm:mb-0">
                                        <div class="flex items-center">
                                            <?php
                                                $statusClassAtividade = 'bg-yellow-100 text-yellow-800';
                                                if ($atividade->status === 'Aprovada') $statusClassAtividade = 'bg-green-100 text-green-800';
                                                elseif ($atividade->status === 'Rejeitada') $statusClassAtividade = 'bg-red-100 text-red-800';
                                                elseif ($atividade->status === 'enviado') $statusClassAtividade = 'bg-blue-100 text-blue-800';
                                                elseif ($atividade->status === 'Em Análise') $statusClassAtividade = 'bg-purple-100 text-purple-800';
                                            ?>
                                            <span class="font-medium capitalize px-2 py-1 rounded-full text-xs mr-3 <?php echo e($statusClassAtividade); ?>"><?php echo e($atividade->status); ?></span>
                                            <p class="font-semibold"><?php echo e($atividade->tipoDeAtividade->nome ?? 'Regra não encontrada'); ?></p>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1 ml-4"><?php echo e($atividade->descricao_customizada); ?></p>
                                        
                                        <div class="mt-2 ml-4 pl-3 border-l-2 border-gray-200 text-xs text-gray-800">
                                            <?php if(str_contains(strtolower($atividade->tipoDeAtividade->nome), 'aproveitamento acadêmico')): ?>
                                                <p><strong>Média Declarada no Perfil:</strong> <?php echo e($candidato->media_aproveitamento ?? 'N/A'); ?></p>
                                            <?php elseif($atividade->tipoDeAtividade->unidade_medida === 'horas'): ?>
                                                <p><strong>Horas Declaradas:</strong> <?php echo e($atividade->carga_horaria ?? 'N/A'); ?></p>
                                            <?php elseif($atividade->tipoDeAtividade->unidade_medida === 'meses'): ?>
                                                <p><strong>Período Declarado:</strong> de <?php echo e(optional($atividade->data_inicio)->format('d/m/Y') ?? 'N/A'); ?> a <?php echo e(optional($atividade->data_fim)->format('d/m/Y') ?? 'N/A'); ?></p>
                                            <?php elseif(str_contains(strtolower($atividade->tipoDeAtividade->nome), 'semestres cursados') || $atividade->tipoDeAtividade->unidade_medida === 'semestre'): ?>
                                                <p><strong>Semestres Declarados na Atividade:</strong> <?php echo e($atividade->semestres_declarados ?? 'N/A'); ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($atividade->status === 'Rejeitada' && $atividade->motivo_rejeicao): ?>
                                            <div class="text-xs text-red-700 mt-2 p-2 bg-red-50 rounded-md">
                                                <p><strong>Motivo:</strong> <?php echo e($atividade->motivo_rejeicao); ?></p>
                                                <?php if($atividade->prazo_recurso_ate): ?>
                                                    <?php if(\Carbon\Carbon::now()->lt($atividade->prazo_recurso_ate)): ?>
                                                        <p class="mt-1 text-blue-700">
                                                            <strong>Prazo para Recurso:</strong> <?php echo e(\Carbon\Carbon::parse($atividade->prazo_recurso_ate)->format('d/m/Y H:i')); ?>

                                                        </p>
                                                    <?php else: ?>
                                                        <p class="mt-1 text-gray-600"><strong>Prazo para Recurso Encerrado.</strong></p>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex items-center space-x-2 flex-shrink-0">
                                        <a href="<?php echo e(route('candidato.atividades.show', $atividade)); ?>" target="_blank" class="px-3 py-1.5 bg-gray-600 text-white rounded-md text-xs hover:bg-gray-700">Visualizar</a>
                                        
                                        <?php if($atividade->status !== 'Aprovada'): ?>
                                            <form action="<?php echo e(route('admin.atividades.aprovar', $atividade->id)); ?>" method="POST" onsubmit="return confirm('Aprovar este item?');">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs hover:bg-green-700">Aprovar Item</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if($atividade->status !== 'Rejeitada'): ?>
                                            <button @click="showRejectionModal = true; rejectionAction = '<?php echo e(route('admin.atividades.rejeitar', $atividade->id)); ?>'" type="button" class="px-3 py-1.5 bg-red-600 text-white rounded-md text-xs hover:bg-red-700">Rejeitar Item</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div x-show="tab === 'acoes'" x-transition x-cloak>
                        <div class="mt-8 pt-6 border-t p-4 bg-gray-100 rounded-lg">
                           <h3 class="text-lg font-semibold text-gray-800 mb-2">Painel de Ações do Administrador</h3>
                           
                           
                           <div class="mt-6">
                               <h4 class="text-lg font-bold text-gray-800 mb-4">Histórico e Análise de Recursos</h4>

                               <?php if($candidato->recurso_historico && count($candidato->recurso_historico) > 0): ?>
                                   <div class="space-y-6">
                                       <?php $__currentLoopData = $candidato->recurso_historico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $recurso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <div class="p-6 border rounded-lg 
                                               <?php if(empty($recurso['decisao_admin'])): ?> bg-purple-50 border-purple-200 
                                               <?php elseif(strtolower($recurso['decisao_admin']) === 'deferido'): ?> bg-green-50 border-green-200
                                               <?php else: ?> bg-red-50 border-red-200 
                                               <?php endif; ?>">
                                               
                                               <div class="flex flex-wrap justify-between items-center pb-3 border-b 
                                                   <?php if(empty($recurso['decisao_admin'])): ?> border-purple-200
                                                   <?php elseif(strtolower($recurso['decisao_admin']) === 'deferido'): ?> border-green-200
                                                   <?php else: ?> border-red-200
                                                   <?php endif; ?>">
                                                   <h5 class="font-bold text-md text-gray-900">
                                                       Recurso #<?php echo e(count($candidato->recurso_historico) - $index); ?>

                                                       <span class="ml-2 text-xs font-medium text-gray-500">
                                                           (Enviado em: <?php echo e(\Carbon\Carbon::parse($recurso['data_envio'])->format('d/m/Y H:i')); ?>)
                                                       </span>
                                                   </h5>
                                                   <?php if(!empty($recurso['decisao_admin'])): ?>
                                                       <span class="px-3 py-1 text-xs font-bold rounded-full mt-2 sm:mt-0
                                                           <?php if(strtolower($recurso['decisao_admin']) === 'deferido'): ?> bg-green-200 text-green-800 
                                                           <?php else: ?> bg-red-200 text-red-800 <?php endif; ?>">
                                                           <?php echo e(ucfirst($recurso['decisao_admin'])); ?>

                                                       </span>
                                                   <?php else: ?>
                                                       <span class="px-3 py-1 text-xs font-bold rounded-full mt-2 sm:mt-0 bg-yellow-200 text-yellow-800">
                                                           Em Análise
                                                       </span>
                                                   <?php endif; ?>
                                               </div>
                                               
                                               <div class="mt-4">
                                                   <p class="text-sm font-semibold text-gray-700">Argumento do Candidato:</p>
                                                   <div class="mt-1 text-sm text-gray-800 bg-white p-3 rounded-md border whitespace-pre-wrap"><?php echo e($recurso['argumento_candidato']); ?></div>
                                               </div>

                                               <?php if(!empty($recurso['decisao_admin'])): ?>
                                                   <div class="mt-4">
                                                       <p class="text-sm font-semibold text-gray-700">Justificativa do Administrador:</p>
                                                       <div class="mt-1 text-sm text-gray-800 bg-white p-3 rounded-md border whitespace-pre-wrap"><?php echo e($recurso['justificativa_admin'] ?? 'Não foi fornecida uma justificativa.'); ?></div>
                                                   </div>
                                               <?php else: ?>
                                                   <div class="mt-6 pt-4 border-t border-purple-200">
                                                       <p class="text-sm font-semibold text-gray-800 mb-2">Tomar Decisão:</p>
                                                       <div class="flex items-center gap-4">
                                                            <button 
                                                                @click="showResourceApprovalModal = true; resourceApprovalAction = '<?php echo e(route('admin.recursos.deferir', ['candidato' => $candidato, 'recurso_index' => $index])); ?>'" 
                                                                type="button" 
                                                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-semibold">
                                                                Deferir Recurso
                                                            </button>
                                                            <button 
                                                                @click="showResourceDenialModal = true; resourceDenialAction = '<?php echo e(route('admin.recursos.indeferir', ['candidato' => $candidato, 'recurso_index' => $index])); ?>'" 
                                                                type="button" 
                                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-semibold">
                                                                Indeferir Recurso
                                                            </button>
                                                       </div>
                                                   </div>
                                               <?php endif; ?>
                                           </div>
                                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   </div>
                               <?php else: ?>
                                   <div class="text-center py-6 bg-gray-50 rounded-lg border">
                                       <p class="text-sm text-gray-500">Nenhum recurso foi interposto por este candidato.</p>
                                   </div>
                               <?php endif; ?>
                           </div>

                           
                           <?php
                                // Verifica se há algum recurso em análise no histórico.
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
                               <div class="mt-8 pt-6 border-t border-gray-300">
                                    <p class="text-sm text-gray-600 mb-4">Após analisar todas as informações, use os botões abaixo para alterar o status da inscrição.</p>
                                   
                                   <?php if($prazosAtivos): ?>
                                       <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                                           <p class="font-bold">Ações Finais Bloqueadas</p>
                                           <p>O candidato possui uma ou mais atividades com prazo de recurso em andamento. Aguarde o término do prazo para prosseguir.</p>
                                       </div>
                                   <?php else: ?>
                                       <?php if($candidato->status === 'Em Análise'): ?>
                                           <form action="<?php echo e(route('admin.candidatos.update', $candidato->id)); ?>" method="POST" onsubmit="return confirm('Você tem certeza que deseja alterar o status desta inscrição?');">
                                               <?php echo csrf_field(); ?>
                                               <?php echo method_field('PUT'); ?>
                                               <div class="space-y-4">
                                                   <div>
                                                       <label for="admin_observacao" class="block text-sm font-medium text-gray-700">Justificativa / Observação</label>
                                                       <textarea name="admin_observacao" id="admin_observacao" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e($candidato->admin_observacao); ?></textarea>
                                                   </div>
                                                   <div class="flex items-center space-x-4">
                                                       <button type="submit" name="status" value="Aprovado" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Aprovar Inscrição</button>
                                                       <button @click="showProfileRejectionModal = true" type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">Rejeitar Inscrição</button>
                                                   </div>
                                               </div>
                                           </form>
                                       <?php elseif($candidato->status === 'Aprovado'): ?>
                                           <form action="<?php echo e(route('admin.candidatos.homologar', $candidato->id)); ?>" method="POST" class="w-full mt-4">
                                               <?php echo csrf_field(); ?>
                                               <div class="bg-yellow-50 p-4 rounded-lg mt-4 mb-4">
                                                   <p class="font-bold text-yellow-800 mb-2">Ação: Homologar Candidato</p>
                                                   <div class="mb-3">
                                                       <label for="ato_homologacao" class="block text-sm font-medium text-gray-700">Número/Referência do Ato de Homologação <span class="text-red-500">*</span></label>
                                                       <input type="text" name="ato_homologacao" id="ato_homologacao" required 
                                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                                              value="<?php echo e(old('ato_homologacao', $candidato->ato_homologacao)); ?>">
                                                       <?php $__errorArgs = ['ato_homologacao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                           <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                                       <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                   </div>
                                                   <div class="mb-3">
                                                       <label for="homologacao_observacoes" class="block text-sm font-medium text-gray-700">Observações (Opcional)</label>
                                                       <textarea name="homologacao_observacoes" id="homologacao_observacoes" rows="3" 
                                                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e(old('homologacao_observacoes', $candidato->homologacao_observacoes)); ?></textarea>
                                                       <?php $__errorArgs = ['homologacao_observacoes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                           <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                                       <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                   </div>
                                                   <button type="submit" class="w-full px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-medium">Homologar Candidato</button>
                                               </div>
                                           </form>
                                       <?php elseif($candidato->status === 'Homologado'): ?>
                                           <div class="bg-blue-50 p-4 rounded-lg mt-4 mb-4">
                                               <p class="font-bold text-blue-800 mb-2">Candidato Homologado!</p>
                                               <p class="text-sm text-blue-700">Ato de Homologação: <span class="font-medium"><?php echo e($candidato->ato_homologacao ?? 'N/A'); ?></span></p>
                                               <p class="text-sm text-blue-700">Homologado em: <span class="font-medium"><?php echo e($candidato->homologado_em ? $candidato->homologado_em->format('d/m/Y H:i') : 'N/A'); ?></span></p>
                                               <?php if($candidato->homologacao_observacoes): ?>
                                                   <p class="text-sm text-blue-700 mt-2">Observações: <span class="font-medium"><?php echo e($candidato->homologacao_observacoes); ?></span></p>
                                               <?php endif; ?>
                                           </div>
                                       <?php else: ?>
                                           <div class="bg-gray-50 p-4 rounded-lg mt-4 mb-4">
                                               <p class="font-bold text-gray-800 mb-2">Ações Atuais:</p>
                                               <p class="text-sm text-gray-700">O status do candidato não permite homologação ou aprovação direta no momento.</p>
                                           </div>
                                       <?php endif; ?>
                                   <?php endif; ?>
                               </div>
                           <?php endif; ?>
                        </div>
                    </div>

                    
                    
                    <div x-show="showRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showRejectionModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Justificar Rejeição</h3>
                            <form :action="rejectionAction" method="POST">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label for="motivo_rejeicao" class="block text-sm font-medium text-gray-700">Por favor, descreva o motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="motivo_rejeicao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showRejectionModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Rejeição</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    
                    <div x-show="showProfileRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showProfileRejectionModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rejeitar Inscrição do Candidato</h3>
                            <form action="<?php echo e(route('admin.candidatos.update', $candidato->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="Rejeitado">
                                <div>
                                    <label for="profile_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Por favor, descreva o motivo da rejeição da inscrição:</label>
                                    <textarea name="admin_observacao" id="profile_motivo_rejeicao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showProfileRejectionModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Rejeição da Inscrição</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    
                    <div x-show="showDocRejectionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showDocRejectionModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Justificar Rejeição do Documento</h3>
                            <form :action="docRejectionAction" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="rejeitado">
                                <div>
                                    <label for="doc_motivo_rejeicao" class="block text-sm font-medium text-gray-700">Por favor, descreva o motivo da rejeição:</label>
                                    <textarea name="motivo_rejeicao" id="doc_motivo_rejeicao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10"></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showDocRejectionModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Rejeição</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    
                    <div x-show="showResourceDenialModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceDenialModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Indeferir Recurso</h3>
                            <form :action="resourceDenialAction" method="POST">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label for="indeferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa Final (será visível para o candidato)</label>
                                    <textarea name="justificativa_admin" id="indeferimento_motivo" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required minlength="10" placeholder="Ex: Os argumentos apresentados não alteram a decisão inicial, pois o requisito X do edital não foi cumprido."></textarea>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showResourceDenialModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Confirmar Indeferimento</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    
                    <div x-show="showResourceApprovalModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50" style="display: none;">
                        <div @click.away="showResourceApprovalModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Deferir Recurso</h3>
                             <form :action="resourceApprovalAction" method="POST">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label for="deferimento_motivo" class="block text-sm font-medium text-gray-700">Justificativa da Decisão (Opcional)</label>
                                    <textarea name="justificativa_admin" id="deferimento_motivo" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: Após reanálise da documentação, a pontuação foi ajustada."></textarea>
                                </div>
                                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                                    <strong>Atenção:</strong> Ao deferir este recurso, você está concordando com o candidato. É sua responsabilidade reanalisar as atividades e documentos e fazer os ajustes necessários manualmente.
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button @click="showResourceApprovalModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm">Cancelar</button>
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">Confirmar Deferimento</button>
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