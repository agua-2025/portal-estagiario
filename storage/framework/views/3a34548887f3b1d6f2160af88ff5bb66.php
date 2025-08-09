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
            <h2 class="text-2xl font-bold text-gray-800">
                📊 Construtor de Relatórios
            </h2>
            </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Card Principal -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <!-- Header do Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <p class="text-white text-sm opacity-90">Configure os filtros e colunas para gerar relatórios personalizados</p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Seção de Filtros -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">1</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Filtros de Pesquisa</h3>
                            </div>
                            <button id="add-filter-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Adicionar Filtro
                            </button>
                        </div>
                        <div id="filtros-container" class="space-y-3">
                            <!-- Filtros serão adicionados aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Seção de Colunas -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-sm">2</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Colunas do Relatório</h3>
                        </div>
                        <div id="colunas-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <!-- Checkboxes de colunas serão adicionados aqui -->
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button id="apply-filters-btn" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            GERAR RELATÓRIO
                        </button>
                        <button id="export-pdf-btn" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            EXPORTAR PDF
                        </button>
                    </div>

                    <!-- Seção de Resultados -->
                    <div class="border-t pt-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-sm">3</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Resultados</h3>
                        </div>
                        
                        <!-- Loading Spinner -->
                        <div id="loading-spinner" style="display: none;" class="flex flex-col items-center justify-center py-12">
                            <div class="relative">
                                <div class="w-16 h-16 border-4 border-blue-200 rounded-full"></div>
                                <div class="w-16 h-16 border-4 border-blue-600 rounded-full animate-spin border-t-transparent absolute top-0 left-0"></div>
                            </div>
                            <p class="mt-4 text-gray-600 font-medium">Carregando dados...</p>
                        </div>

                        <!-- Tabela de Resultados -->
                        <div class="overflow-hidden rounded-lg border border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr id="resultados-head"></tr>
                                    </thead>
                                    <tbody id="resultados-body" class="bg-white divide-y divide-gray-200"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paginação -->
                        <div id="pagination-links" class="mt-4 flex justify-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    /* Estilos adicionais para melhorar a aparência */
    .filter-row {
        background: white;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .filter-row:hover {
        border-color: #3b82f6;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }
    
    .column-checkbox {
        accent-color: #3b82f6;
    }
    
    .column-checkbox:checked + label {
        color: #1e40af;
        font-weight: 500;
    }
    
    /* Melhora no visual dos selects e inputs */
    select, input[type="text"], input[type="number"], input[type="date"] {
        transition: all 0.2s;
    }
    
    select:focus, input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Animação suave para os botões */
    button {
        transition: all 0.2s ease;
    }
    
    /* Estilo para a tabela */
    tbody tr:hover {
        background-color: #f9fafb;
    }
    
    /* Checkbox container */
    #colunas-container > div {
        padding: 0.5rem;
        background: white;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    
    #colunas-container > div:hover {
        border-color: #3b82f6;
        background: #f0f9ff;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- CONFIGURAÇÃO INICIAL E VARIÁVEIS DO BACKEND ---
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('ERRO CRÍTICO: Meta tag CSRF não encontrada.');
        return;
    }
    const csrfToken = csrfMeta.getAttribute('content');
    const filterUrl = '<?php echo e(route("admin.candidatos.relatorios.filtrar")); ?>';

    // Opções passadas pelo Controller do Laravel
    const cursoOptions = <?php echo json_encode($cursos ?? [], 15, 512) ?>;
    const instituicaoOptions = <?php echo json_encode($instituicoes ?? [], 15, 512) ?>;
    const statusOptions = { 
        'Inscrição Incompleta': 'Inscrição Incompleta', 
        'Aprovado': 'Aprovado', 
        'Rejeitado': 'Rejeitado', 
        'Homologado': 'Homologado', 
        'Convocado': 'Convocado' 
    };

    // Definição dos campos para filtros e colunas
    const filterableFields = {
        'candidatos.status': { label: 'Status do Candidato', type: 'select', options: statusOptions },
        'candidatos.curso_id': { label: 'Curso', type: 'select', options: cursoOptions },
        'candidatos.instituicao_id': { label: 'Instituição', type: 'select', options: instituicaoOptions },
        'candidatos.pontuacao_final': { label: 'Pontuação', type: 'number' },
        'candidatos.nome_completo': { label: 'Nome do Candidato', type: 'text' },
        'candidatos.created_at': { label: 'Data de Inscrição', type: 'daterange' },
        'candidatos.homologado_em': { label: 'Data de Homologação', type: 'daterange' },
        'candidatos.convocado_em': { label: 'Data de Convocação', type: 'daterange' }
    };
    
    const displayableColumns = {
        'nome_completo': 'Nome',
        'cpf': 'CPF',
        'email': 'Email',
        'telefone': 'Telefone',
        'curso_nome': 'Curso',
        'instituicao_nome': 'Instituição',
        'status': 'Status',
        'pontuacao_final': 'Pontuação',
        'acoes': 'Ações'
    };

    // --- ELEMENTOS DO DOM ---
    const filtrosContainer = document.getElementById('filtros-container');
    const colunasContainer = document.getElementById('colunas-container');
    const addFilterBtn = document.getElementById('add-filter-btn');
    const applyFiltersBtn = document.getElementById('apply-filters-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const resultadosHead = document.getElementById('resultados-head');
    const resultadosBody = document.getElementById('resultados-body');
    const paginationContainer = document.getElementById('pagination-links');

    // --- FUNÇÕES DE CRIAÇÃO DINÂMICA DE ELEMENTOS ---
    function populateColumnsSelector() {
        colunasContainer.innerHTML = '';
        const defaultColumns = ['nome_completo', 'curso_nome', 'status', 'pontuacao_final'];
        
        for (const [key, label] of Object.entries(displayableColumns)) {
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2';
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `coluna-${key}`;
            checkbox.name = 'colunas[]';
            checkbox.value = key;
            checkbox.className = 'column-checkbox w-4 h-4 rounded border-gray-300 focus:ring-blue-500';
            checkbox.checked = defaultColumns.includes(key);

            const labelEl = document.createElement('label');
            labelEl.htmlFor = `coluna-${key}`;
            labelEl.className = 'text-sm text-gray-700 cursor-pointer select-none';
            labelEl.textContent = label;

            div.append(checkbox, labelEl);
            colunasContainer.append(div);
        }
    }

    function addFilterRow() {
        const row = document.createElement('div');
        row.className = 'filter-row flex items-center gap-3';

        // Dropdown de campos
        const fieldSelect = document.createElement('select');
        fieldSelect.name = 'field';
        fieldSelect.className = 'field-select flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm';
        fieldSelect.innerHTML = `<option value="">Selecione um campo...</option>` +
            Object.entries(filterableFields).map(([key, val]) => `<option value="${key}">${val.label}</option>`).join('');

        // Dropdown de operadores
        const operatorSelect = document.createElement('select');
        operatorSelect.name = 'operator';
        operatorSelect.className = 'operator-select px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm';
        operatorSelect.style.display = 'none';
        operatorSelect.innerHTML = `
            <option value="=">igual a</option>
            <option value=">">maior que</option>
            <option value="<">menor que</option>
        `;

        // Container para o campo de valor
        const valueContainer = document.createElement('div');
        valueContainer.className = 'value-container flex-1';

        // Botão de remover
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-filter-btn px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium';
        removeBtn.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        `;

        row.append(fieldSelect, operatorSelect, valueContainer, removeBtn);
        filtrosContainer.append(row);
    }

    function updateValueInput(selectElement) {
        const row = selectElement.closest('.filter-row');
        const valueContainer = row.querySelector('.value-container');
        const operatorSelect = row.querySelector('.operator-select');
        const selectedFieldKey = selectElement.value;

        valueContainer.innerHTML = '';
        if (!selectedFieldKey) {
            operatorSelect.style.display = 'none';
            return;
        }

        const fieldConfig = filterableFields[selectedFieldKey];
        operatorSelect.style.display = fieldConfig.type === 'number' ? 'block' : 'none';

        if (fieldConfig.type === 'select') {
            const select = document.createElement('select');
            select.name = 'value';
            select.className = 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm';
            select.innerHTML = `<option value="">Selecione...</option>` +
                Object.entries(fieldConfig.options).map(([key, label]) => `<option value="${key}">${label}</option>`).join('');
            valueContainer.append(select);
        } else if (fieldConfig.type === 'daterange') {
            valueContainer.innerHTML = `
                <div class="flex items-center gap-2">
                    <input type="date" name="value_inicio" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <span class="text-gray-500 text-sm">até</span>
                    <input type="date" name="value_fim" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            `;
        } else {
            const input = document.createElement('input');
            input.type = fieldConfig.type;
            input.name = 'value';
            input.className = 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm';
            input.placeholder = 'Digite o valor';
            valueContainer.append(input);
        }
    }

    // --- LÓGICA PRINCIPAL DE BUSCA E RENDERIZAÇÃO ---
    async function fetchAndRenderResults(url = filterUrl) {
        loadingSpinner.style.display = 'flex';
        resultadosBody.innerHTML = '';
        resultadosHead.innerHTML = '';
        paginationContainer.innerHTML = '';

        const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(cb => cb.value);
        
        const filters = Array.from(filtrosContainer.querySelectorAll('.filter-row')).map(row => {
            const field = row.querySelector('[name="field"]').value;
            if (!field) return null;

            const fieldConfig = filterableFields[field];
            const filterData = { field };

            if (fieldConfig.type === 'daterange') {
                filterData.value_inicio = row.querySelector('[name="value_inicio"]').value;
                filterData.value_fim = row.querySelector('[name="value_fim"]').value;
            } else {
                filterData.value = row.querySelector('[name="value"]').value;
                if (fieldConfig.type === 'number') {
                    filterData.operator = row.querySelector('[name="operator"]').value;
                }
            }
            return filterData;
        }).filter(Boolean);

        const payload = {
            colunas: selectedColumns,
            filtros: filters
        };

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `Erro na resposta da rede: ${response.status}`);
            }

            const result = await response.json();

            // Renderiza o cabeçalho da tabela
            resultadosHead.innerHTML = selectedColumns.map(colKey => 
                `<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${displayableColumns[colKey] || colKey}</th>`
            ).join('');

            // Renderiza o corpo da tabela
            if (result.data && result.data.length > 0) {
                let bodyHtml = '';
                result.data.forEach(item => {
                    bodyHtml += '<tr class="hover:bg-gray-50">';
                    selectedColumns.forEach(colKey => {
                        if (colKey === 'acoes') {
                            bodyHtml += `
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/admin/candidatos/${item.id}/perfil-pdf" 
                                       target="_blank" 
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        PDF
                                    </a>
                                </td>
                            `;
                        } else {
                            let value = item[colKey] ?? '';
                            // Adiciona badge colorido para status
                            if (colKey === 'status') {
                                const statusColors = {
                                    'Aprovado': 'bg-green-100 text-green-800',
                                    'Rejeitado': 'bg-red-100 text-red-800',
                                    'Homologado': 'bg-blue-100 text-blue-800',
                                    'Convocado': 'bg-purple-100 text-purple-800',
                                    'Inscrição Incompleta': 'bg-yellow-100 text-yellow-800'
                                };
                                const colorClass = statusColors[value] || 'bg-gray-100 text-gray-800';
                                value = `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${colorClass}">${value}</span>`;
                            }
                            bodyHtml += `<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${value}</td>`;
                        }
                    });
                    bodyHtml += '</tr>';
                });
                resultadosBody.innerHTML = bodyHtml;
            } else {
                resultadosBody.innerHTML = `
                    <tr>
                        <td colspan="${selectedColumns.length || 1}" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Nenhum resultado encontrado</p>
                        </td>
                    </tr>
                `;
            }

        } catch (error) {
            console.error('Erro ao buscar dados:', error);
            resultadosBody.innerHTML = `
                <tr>
                    <td colspan="${selectedColumns.length || 1}" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-red-600">Erro ao carregar os dados</p>
                        <p class="text-xs text-gray-500 mt-1">Verifique o console para mais detalhes</p>
                    </td>
                </tr>
            `;
        } finally {
            loadingSpinner.style.display = 'none';
        }
    }
    
    // --- EVENT LISTENERS ---
    addFilterBtn.addEventListener('click', addFilterRow);
    applyFiltersBtn.addEventListener('click', () => fetchAndRenderResults());

    filtrosContainer.addEventListener('click', e => {
        if (e.target.closest('.remove-filter-btn')) {
            e.target.closest('.filter-row').remove();
        }
    });

    filtrosContainer.addEventListener('change', e => {
        if (e.target.classList.contains('field-select')) {
            updateValueInput(e.target);
        }
    });

    paginationContainer.addEventListener('click', e => {
        const button = e.target.closest('.pagination-button');
        if (button && !button.disabled && button.dataset.url) {
            fetchAndRenderResults(button.dataset.url);
        }
    });

    const exportPdfBtn = document.getElementById('export-pdf-btn');
    exportPdfBtn.addEventListener('click', function() {
        const filters = Array.from(filtrosContainer.querySelectorAll('.filter-row')).map(row => {
            const field = row.querySelector('[name="field"]').value;
            if (!field) return null;
            const fieldConfig = filterableFields[field];
            const filterData = { field };
            if (fieldConfig.type === 'daterange') {
                filterData.value_inicio = row.querySelector('[name="value_inicio"]').value;
                filterData.value_fim = row.querySelector('[name="value_fim"]').value;
            } else {
                filterData.value = row.querySelector('[name="value"]').value;
                if (fieldConfig.type === 'number') {
                    filterData.operator = row.querySelector('[name="operator"]').value;
                }
            }
            return filterData;
        }).filter(Boolean);

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

    // --- INICIALIZAÇÃO ---
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