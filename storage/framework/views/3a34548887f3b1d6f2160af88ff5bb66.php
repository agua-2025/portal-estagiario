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
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">
                    Construtor de Relatórios
                </h2>
                <p class="mt-1 text-sm text-gray-500">Gere relatórios personalizados dos candidatos</p>
            </div>
            <div class="text-right">
                
                
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Filtros de Pesquisa</h3>
                                <p class="text-sm text-gray-500">Configure os filtros para refinar os resultados</p>
                            </div>
                        </div>
                        <button id="add-filter-btn" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                            Adicionar Filtro
                        </button>
                    </div>
                    
                    <div id="filtros-container" class="space-y-3"></div>
                </div>
            </div>

            <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Colunas do Relatório</h3>
                            <p class="text-sm text-gray-500">Selecione as informações que deseja visualizar</p>
                        </div>
                    </div>
                    <div id="colunas-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                </div>
            </div>

            <div class="flex gap-4 mb-6">
                <button id="apply-filters-btn" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Gerar Relatório
                </button>
                <button id="export-pdf-btn" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Exportar PDF
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a1 1 0 001 1h4a1 1 0 001-1v-1m3-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v6m0 4h12a2 2 0 002-2v-3a1 1 0 00-1-1H5a1 1 0 00-1 1v3a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Resultados</h3>
                            <p class="text-sm text-gray-500">Visualize e gerencie os dados filtrados</p>
                        </div>
                    </div>
                </div>

                <div id="loading-spinner" style="display: none;" class="flex flex-col items-center justify-center py-16">
                    <div class="relative"><div class="w-12 h-12 rounded-full border-4 border-gray-200"></div><div class="w-12 h-12 rounded-full border-4 border-blue-600 border-t-transparent animate-spin absolute top-0 left-0"></div></div>
                    <p class="mt-4 text-sm text-gray-500">Carregando dados...</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr id="resultados-head"></tr>
                        </thead>
                        <tbody id="resultados-body" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>

                <div id="pagination-links" class="px-6 py-4 border-t border-gray-100"></div>
            </div>
        </div>
    </div>

<style>
    .filter-row { background: #f9fafb; padding: 12px; border-radius: 8px; border: 1px solid #e5e7eb; }
    .status-badge { display: inline-flex; align-items: center; padding: 2px 10px; font-size: 12px; font-weight: 500; border-radius: 9999px; }
    select, input[type="text"], input[type="number"], input[type="date"] { border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
    select:focus, input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    tbody tr:hover { background-color: #f9fafb; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) { console.error('ERRO CRÍTICO: Meta tag CSRF não encontrada.'); return; }
    const csrfToken = csrfMeta.getAttribute('content');
    const filterUrl = '<?php echo e(route("admin.candidatos.relatorios.filtrar")); ?>';

    const cursoOptions = <?php echo json_encode($cursos ?? [], 15, 512) ?>;
    const instituicaoOptions = <?php echo json_encode($instituicoes ?? [], 15, 512) ?>;
    const statusOptions = { 'Inscrição Incompleta': 'Inscrição Incompleta', 'Aprovado': 'Aprovado', 'Rejeitado': 'Rejeitado', 'Homologado': 'Homologado', 'Convocado': 'Convocado' };

    const filterableFields = {
        'candidatos.status': { label: 'Status', type: 'select', options: statusOptions },
        'candidatos.curso_id': { label: 'Curso', type: 'select', options: cursoOptions },
        'candidatos.instituicao_id': { label: 'Instituição', type: 'select', options: instituicaoOptions },
        'candidatos.pontuacao_final': { label: 'Pontuação', type: 'number' },
        'candidatos.nome_completo': { label: 'Nome', type: 'text' },
        'candidatos.created_at': { label: 'Data de Inscrição', type: 'daterange' },
        'candidatos.homologado_em': { label: 'Data de Homologação', type: 'daterange' },
        'candidatos.convocado_em': { label: 'Data de Convocação', type: 'daterange' }
    };
    
    const displayableColumns = {
        'nome_completo': 'Nome','cpf': 'CPF','email': 'E-mail','telefone': 'Telefone',
        'curso_nome': 'Curso','instituicao_nome': 'Instituição','status': 'Status',
        'pontuacao_final': 'Pontuação','acoes': 'Ações'
    };

    const filtrosContainer = document.getElementById('filtros-container');
    const colunasContainer = document.getElementById('colunas-container');
    const addFilterBtn = document.getElementById('add-filter-btn');
    const applyFiltersBtn = document.getElementById('apply-filters-btn');
    const exportPdfBtn = document.getElementById('export-pdf-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const resultadosHead = document.getElementById('resultados-head');
    const resultadosBody = document.getElementById('resultados-body');
    const paginationContainer = document.getElementById('pagination-links');

    function populateColumnsSelector() {
        colunasContainer.innerHTML = '';
        const defaultColumns = ['nome_completo', 'curso_nome', 'status', 'pontuacao_final', 'acoes'];
        for (const [key, label] of Object.entries(displayableColumns)) {
            const div = document.createElement('div');
            div.className = 'flex items-center';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `coluna-${key}`;
            checkbox.name = 'colunas[]';
            checkbox.value = key;
            checkbox.className = 'column-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500';
            checkbox.checked = defaultColumns.includes(key);
            const labelEl = document.createElement('label');
            labelEl.htmlFor = `coluna-${key}`;
            labelEl.className = 'ml-2 text-sm text-gray-700 cursor-pointer select-none';
            labelEl.textContent = label;
            div.append(checkbox, labelEl);
            colunasContainer.append(div);
        }
    }

    function addFilterRow() {
        const row = document.createElement('div');
        row.className = 'filter-row flex items-center gap-3';
        const fieldSelect = document.createElement('select');
        fieldSelect.name = 'field';
        fieldSelect.className = 'field-select flex-1 px-3 py-2 text-sm';
        fieldSelect.innerHTML = `<option value="">Selecione um campo...</option>` +
            Object.entries(filterableFields).map(([key, val]) => `<option value="${key}">${val.label}</option>`).join('');
        const operatorSelect = document.createElement('select');
        operatorSelect.name = 'operator';
        operatorSelect.className = 'operator-select px-3 py-2 text-sm';
        operatorSelect.style.display = 'none';
        operatorSelect.innerHTML = `<option value="=">igual a</option><option value=">">maior que</option><option value="<">menor que</option>`;
        const valueContainer = document.createElement('div');
        valueContainer.className = 'value-container flex-1';
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-filter-btn p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors';
        removeBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>`;
        row.append(fieldSelect, operatorSelect, valueContainer, removeBtn);
        filtrosContainer.append(row);
    }

    function updateValueInput(selectElement) {
        const row = selectElement.closest('.filter-row');
        const valueContainer = row.querySelector('.value-container');
        const operatorSelect = row.querySelector('.operator-select');
        const selectedFieldKey = selectElement.value;
        valueContainer.innerHTML = '';
        if (!selectedFieldKey) { operatorSelect.style.display = 'none'; return; }
        const fieldConfig = filterableFields[selectedFieldKey];
        operatorSelect.style.display = fieldConfig.type === 'number' ? 'block' : 'none';
        if (fieldConfig.type === 'select') {
            const select = document.createElement('select');
            select.name = 'value';
            select.className = 'w-full px-3 py-2 text-sm';
            select.innerHTML = `<option value="">Selecione...</option>` +
                Object.entries(fieldConfig.options).map(([key, label]) => `<option value="${key}">${label}</option>`).join('');
            valueContainer.append(select);
        } else if (fieldConfig.type === 'daterange') {
            valueContainer.innerHTML = `<div class="flex items-center gap-2"><input type="date" name="value_inicio" class="flex-1 px-3 py-2 text-sm"><span class="text-gray-400 text-sm">até</span><input type="date" name="value_fim" class="flex-1 px-3 py-2 text-sm"></div>`;
        } else {
            const input = document.createElement('input');
            input.type = fieldConfig.type;
            input.name = 'value';
            input.className = 'w-full px-3 py-2 text-sm';
            input.placeholder = 'Digite o valor...';
            valueContainer.append(input);
        }
    }
    
    function getActiveFilters() {
        return Array.from(filtrosContainer.querySelectorAll('.filter-row')).map(row => {
            const field = row.querySelector('[name="field"]').value;
            if (!field) return null;
            const fieldConfig = filterableFields[field];
            const filterData = { field };
            if (fieldConfig.type === 'daterange') {
                const inicio = row.querySelector('[name="value_inicio"]').value;
                const fim = row.querySelector('[name="value_fim"]').value;
                if (!inicio || !fim) return null;
                filterData.value_inicio = inicio;
                filterData.value_fim = fim;
            } else {
                const value = row.querySelector('[name="value"]').value;
                if (!value) return null;
                filterData.value = value;
                if (fieldConfig.type === 'number') {
                    filterData.operator = row.querySelector('[name="operator"]').value;
                }
            }
            return filterData;
        }).filter(Boolean);
    }
    
    async function fetchAndRenderResults(url = filterUrl) {
        loadingSpinner.style.display = 'flex';
        resultadosBody.innerHTML = '';
        resultadosHead.innerHTML = '';
        paginationContainer.innerHTML = '';
        const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(cb => cb.value);
        const payload = { colunas: selectedColumns, filtros: getActiveFilters() };
        console.log("Enviando para o backend:", payload);
        try {
            const response = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify(payload) });
            if (!response.ok) { const errorData = await response.json(); throw new Error(errorData.message || `Erro: ${response.status}`); }
            const result = await response.json();
            
            resultadosHead.innerHTML = `<tr>${selectedColumns.map(colKey => `<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${displayableColumns[colKey] || colKey}</th>`).join('')}</tr>`;
            
            if (result.data && result.data.length > 0) {
                let bodyHtml = '';
                result.data.forEach(item => {
                    bodyHtml += '<tr>';
                    selectedColumns.forEach(colKey => {
                        let cellContent = '';
                        if (colKey === 'acoes') {
                            if (item.perfil_pdf_url) { cellContent += `<a href="${item.perfil_pdf_url}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-md hover:bg-blue-100 transition-colors">Perfil PDF</a>`; }
                            if (item.status === 'Convocado') {
    cellContent += `<a href="/admin/candidatos/${item.id}/convocacao-pdf" target="_blank" class="inline-flex items-center px-3 py-1.5 ml-2 bg-green-50 text-green-700 text-xs font-medium rounded-md hover:bg-green-100 transition-colors">Convocação PDF</a>`;
}
                        } else if (colKey === 'status') {
                            const statusColors = { 'Aprovado': 'bg-green-50 text-green-700 border-green-200', 'Rejeitado': 'bg-red-50 text-red-700 border-red-200', 'Homologado': 'bg-blue-50 text-blue-700 border-blue-200', 'Convocado': 'bg-purple-50 text-purple-700 border-purple-200', 'Inscrição Incompleta': 'bg-yellow-50 text-yellow-700 border-yellow-200' };
                            const colorClass = statusColors[item[colKey]] || 'bg-gray-50 text-gray-700 border-gray-200';
                            cellContent = `<span class="status-badge border ${colorClass}">${item[colKey] || ''}</span>`;
                        } else {
                            let value = item[colKey] || '';
                            if (['created_at', 'homologado_em', 'convocado_em'].includes(colKey) && value) { value = new Date(value).toLocaleDateString('pt-BR', { timeZone: 'UTC' }); }
                            cellContent = value;
                        }
                        bodyHtml += `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${cellContent}</td>`;
                    });
                    bodyHtml += '</tr>';
                });
                resultadosBody.innerHTML = bodyHtml;
            } else {
                resultadosBody.innerHTML = `<tr><td colspan="${selectedColumns.length || 1}" class="px-6 py-12 text-center"><p class="text-gray-500 text-sm">Nenhum resultado encontrado</p></td></tr>`;
            }

            if (result.links) {
                let paginationHtml = '<nav class="flex items-center justify-between"><div class="flex flex-wrap -mb-1">';
                result.links.forEach(link => {
                    const pageUrl = link.url ? `'${link.url.replace(/"/g, '&quot;')}'` : 'null';
                    const activeClass = link.active ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
                    const disabledClass = !link.url ? 'opacity-50 cursor-not-allowed' : '';
                    paginationHtml += `<button type="button" onclick="window.fetchAndRenderResults(${pageUrl})" class="pagination-button relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium border leading-5 ${activeClass} ${disabledClass}" ${!link.url ? 'disabled' : ''}>${new DOMParser().parseFromString(link.label, 'text/html').body.textContent}</button>`;
                });
                paginationHtml += '</div></nav>';
                paginationContainer.innerHTML = paginationHtml;
            }

        } catch (error) {
            console.error('Erro:', error);
            resultadosBody.innerHTML = `<tr><td colspan="${selectedColumns.length || 1}" class="px-6 py-12 text-center"><p class="text-gray-900 text-sm font-medium">Erro ao carregar dados</p></td></tr>`;
        } finally {
            loadingSpinner.style.display = 'none';
        }
    }
    
    window.fetchAndRenderResults = fetchAndRenderResults;
    addFilterBtn.addEventListener('click', addFilterRow);
    applyFiltersBtn.addEventListener('click', () => fetchAndRenderResults());
    exportPdfBtn.addEventListener('click', function() {
        const filters = getActiveFilters();
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo e(route("admin.candidatos.relatorios.exportar-pdf")); ?>';
        form.target = '_blank';
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        filters.forEach((filter, index) => {
            for (const [key, value] of Object.entries(filter)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `filtros[${index}][${key}]`;
                input.value = value;
                form.appendChild(input);
            }
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });

    filtrosContainer.addEventListener('click', e => { if (e.target.closest('.remove-filter-btn')) e.target.closest('.filter-row').remove(); });
    filtrosContainer.addEventListener('change', e => { if (e.target.classList.contains('field-select')) updateValueInput(e.target); });
    paginationContainer.addEventListener('click', e => {
        const button = e.target.closest('.pagination-button');
        if (button && !button.disabled && button.dataset.url) {
            fetchAndRenderResults(button.dataset.url);
        }
    });

    // Inicialização
    populateColumnsSelector();
    addFilterRow();
});
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\portal-estagiario\resources\views/admin/candidatos/relatorios.blade.php ENDPATH**/ ?>