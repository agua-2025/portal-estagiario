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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-8">
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Bem-vindo, <?php echo e($user->name); ?>!</h3>
                        <p class="text-gray-600 mt-2">Este é o seu Centro de Controle. Siga os passos abaixo para completar a sua inscrição.</p>
                    </div>

                    
                    <?php
                        $candidato = auth()->user()->candidato;
                        $documentosRejeitados = collect();
                        $atividadesRejeitadas = collect();
                        
                        if ($candidato) {
                            $documentosRejeitados = $candidato->documentos()->where('status', 'rejeitado')->get();
                            $atividadesRejeitadas = $candidato->atividades()->where('status', 'Rejeitada')->get();
                        }
                        
                        $temItensRejeitados = $documentosRejeitados->count() > 0 || $atividadesRejeitadas->count() > 0;
                    ?>

             <?php if($candidato && $temItensRejeitados): ?>
    <div class="p-3 bg-red-50 border border-red-200 rounded-lg shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm text-red-800">
                    <span class="font-bold">AÇÃO NECESSÁRIA</span>
                    <span class="mt-1 block text-red-700">Sua inscrição possui itens rejeitados que precisam ser corrigidos:</span>
                </p>
                <div class="mt-2 text-sm">
                    <?php if($documentosRejeitados->count() > 0): ?>
                        <a href="<?php echo e(route('candidato.documentos.index')); ?>" class="inline-flex items-center text-red-700 hover:text-red-800 font-bold underline">
                            Corrigir <?php echo e($documentosRejeitados->count()); ?> documento(s)
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    <?php endif; ?>
                    <?php if($atividadesRejeitadas->count() > 0): ?>
                        <a href="<?php echo e(route('candidato.atividades.index')); ?>" class="mt-1 inline-flex items-center text-red-700 hover:text-red-800 font-bold underline">
                            Verificar <?php echo e($atividadesRejeitadas->count()); ?> atividade(s)
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

                    
                    <?php if(auth()->user()->candidato && 
                    auth()->user()->candidato->status === 'Inscrição Incompleta' && 
                    !$temItensRejeitados): ?>
                        <div class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-base text-yellow-800">
                                        <span class="font-semibold">Atenção!</span> Sua inscrição está aguardando documentos obrigatórios!
                                        <a href="<?php echo e(route('candidato.documentos.index')); ?>" class="inline-flex items-center font-semibold text-yellow-700 hover:text-yellow-600 underline decoration-2 underline-offset-2">
                                            Acesse a seção "Meus Documentos" para completar.
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php elseif(auth()->user()->candidato && strtolower(auth()->user()->candidato->status) === 'homologado'): ?>
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.857a.75.75 0 00-1.06-1.06l-3.25 3.25a.75.75 0 01-1.06 0l-1.75-1.75a.75.75 0 10-1.06 1.06L8.22 12.22a2.25 2.25 0 003.18 0l3.5-3.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-grow">
                                    <p class="text-base text-purple-800">
                                        <span class="font-semibold text-lg">Parabéns!</span> Sua inscrição foi Homologada!
                                        <br>
                                        <?php if(auth()->user()->candidato->ato_homologacao): ?>
                                            <span class="block mt-2 text-sm text-purple-700">Ato de Homologação: <?php echo e(auth()->user()->candidato->ato_homologacao); ?></span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    
                                    <?php if(Auth::user()->candidato?->pode_interpor_recurso && Auth::user()->candidato->homologado_em): ?>
                                        <div class="mt-3 pt-3 border-t border-purple-200">
                                            <p class="text-sm text-purple-700">
                                                <span class="font-semibold">Prazo para recurso aberto:</span> Caso discorde da sua pontuação, o prazo para interpor recurso através do menu "Meu Currículo" encerra-se em 
                                                <?php
                                                    // ✅ LÓGICA DE CÁLCULO 100% CORRETA
                                                    $prazoFinal = Auth::user()->candidato->homologado_em->copy();
                                                    $diasUteisAdicionados = 0;
                                                    $diasUteisParaAdicionar = 2;

                                                    while ($diasUteisAdicionados < $diasUteisParaAdicionar) {
                                                        $prazoFinal->addDay();
                                                        if ($prazoFinal->isWeekday()) {
                                                            $diasUteisAdicionados++;
                                                        }
                                                    }
                                                    $prazoFinal->setTime(17, 0, 0);
                                                ?>
                                                <strong class="text-purple-800"><?php echo e($prazoFinal->format('d/m/Y \à\s H:i')); ?></strong>.
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="<?php echo e(route('candidato.profile.edit')); ?>" class="group block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 hover:from-blue-100 hover:to-blue-200 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-full h-10 w-10 flex items-center justify-center text-white font-bold text-base shadow-md">1</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-blue-900 text-lg">Meu Currículo</h4>
                                    <p class="text-base text-blue-700">Preencha seus dados</p>
                                </div>
                            </div>
                            <?php if(auth()->user()->candidato): ?>
                                <?php
                                    $completionPercentage = auth()->user()->candidato->completion_percentage;
                                ?>
                                <div class="w-full bg-blue-200 rounded-full h-2 mt-4">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: <?php echo e($completionPercentage); ?>%"></div>
                                </div>
                                <p class="text-sm text-right text-blue-600 mt-2 font-medium"><?php echo e($completionPercentage); ?>% completo</p>
                            <?php endif; ?>
                        </a>
                        
                        <a href="<?php echo e(route('candidato.documentos.index')); ?>" class="group block p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 hover:from-green-100 hover:to-green-200 hover:border-green-300 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-full h-10 w-10 flex items-center justify-center text-white font-bold text-base shadow-md">2</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-green-900 text-lg">Documentos</h4>
                                    <p class="text-base text-green-700">Anexe os comprovantes</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-green-400 group-hover:text-green-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('candidato.atividades.index')); ?>" class="group block p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 hover:from-purple-100 hover:to-purple-200 hover:border-purple-300 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-full h-10 w-10 flex items-center justify-center text-white font-bold text-base shadow-md">3</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-purple-900 text-lg">Saia na Frente</h4>
                                    <p class="text-base text-purple-700">Adicione seus pontos</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-purple-400 group-hover:text-purple-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                        </a>
                    </div>

                    
                    <div class="mt-8">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Sua Classificação</h3>
                            <p class="text-gray-600">Veja a sua posição na lista de aprovados para o seu curso.</p>
                        </div>

                        
                        <?php
                            $candidatoLogado = auth()->user()->candidato;
                            $classificacaoDoCurso = collect(); 
                            $regrasDePontuacao = collect(); 

                            if ($candidatoLogado && $candidatoLogado->curso) {
                                $regrasDePontuacao = \App\Models\TipoDeAtividade::orderBy('nome')->get();
                                
                                $todosCandidatosClassificacao = \App\Models\Candidato::whereIn('status', ['Aprovado', 'Homologado'])
                                    ->with(['user', 'curso', 'instituicao', 'atividades.tipoDeAtividade'])
                                    ->get()
                                    ->map(function($cand) use ($regrasDePontuacao) {
                                        $resultado = $cand->calcularPontuacaoDetalhada();
                                        $cand->pontuacao_final = $resultado['total'];
                                        
                                        $boletim = [];
                                        foreach($regrasDePontuacao as $regra) {
                                            $boletim[$regra->nome] = 0;
                                        }
                                        foreach($resultado['detalhes'] as $detalhe) {
                                            if (isset($boletim[$detalhe['nome']])) {
                                                $boletim[$detalhe['nome']] += $detalhe['pontos'];
                                            }
                                        }
                                        $cand->boletim_pontos = $boletim;
                                        
                                        return $cand;
                                    })
                                    ->sortByDesc('pontuacao_final')
                                    ->sortBy(function($cand) { return strtotime($cand->data_nascimento); });
                                
                                $classificacaoDoCurso = $todosCandidatosClassificacao->filter(function($cand) use ($candidatoLogado) {
                                    return $cand->curso_id === $candidatoLogado->curso_id;
                                })->values();
                            }
                        ?>

                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                            <div class="p-6">
                                <?php if($candidatoLogado && $candidatoLogado->curso): ?>
                                    <div class="mb-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-800"><?php echo e($candidato->curso->nome); ?></h4>
                                        </div>
                                    </div>

                                    <?php if($classificacaoDoCurso->isEmpty()): ?>
                                        <div class="text-center py-12">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="mt-4 text-gray-500">A classificação para o seu curso ainda não foi divulgada.</p>
                                        </div>
                                    <?php else: ?>
                                        <?php if(Auth::user()->candidato && !in_array(Auth::user()->candidato->status, ['Aprovado', 'Homologado'])): ?>
                                            <div class="mb-4 p-3 bg-blue-50 text-blue-700 text-sm rounded-lg border border-blue-200">
                                                <p>Esta é a classificação atual dos candidatos aprovados. A sua posição aparecerá aqui assim que a sua inscrição for aprovada pela comissão.</p>
                                            </div>
                                        <?php endif; ?>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full">
                                                <thead>
                                                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Posição</th>
                                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Candidato</th>
                                                        <?php $__currentLoopData = $regrasDePontuacao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e($regra->nome); ?></th>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    <?php $__currentLoopData = $classificacaoDoCurso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $classificado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150 <?php echo e($classificado->user_id === Auth::id() ? 'bg-blue-50 border-l-4 border-blue-400' : ''); ?>">
                                                            <td class="px-3 py-3 text-center">
                                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-xs font-bold <?php echo e($classificado->user_id === Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'); ?>">
                                                                    <?php echo e($index + 1); ?>

                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <div class="text-sm font-medium <?php echo e($classificado->user_id === Auth::id() ? 'text-blue-900' : 'text-gray-900'); ?>">
                                                                        <?php echo e($classificado->user->name); ?>

                                                                        <?php if($classificado->user_id === Auth::id()): ?>
                                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                                Você
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <?php $__currentLoopData = $regrasDePontuacao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <td class="px-3 py-3 text-center text-xs <?php echo e($classificado->user_id === Auth::id() ? 'text-blue-900' : 'text-gray-700'); ?>">
                                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                                        <?php echo e(number_format($classificado->boletim_pontos[$regra->nome] ?? 0, 2, ',', '.')); ?>

                                                                    </span>
                                                                </td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold <?php echo e($classificado->user_id === Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-800 text-white'); ?>">
                                                                    <?php echo e(number_format($classificado->pontuacao_final, 2, ',', '.')); ?>

                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <?php if($classificacaoDoCurso->count() > 10): ?>
                                            <div class="mt-4 text-center">
                                                <p class="text-xs text-gray-500">Mostrando <?php echo e($classificacaoDoCurso->count()); ?> candidatos classificados</p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <p class="mt-4 text-gray-500">Complete o seu perfil e selecione um curso para ver a classificação.</p>
                                        <a href="<?php echo e(route('candidato.profile.edit')); ?>" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                            Completar Perfil
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/dashboard.blade.php ENDPATH**/ ?>