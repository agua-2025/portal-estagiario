<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Construtor de Relatórios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="p-4 mb-6 border rounded-md">
                        <h3 class="mb-4 font-bold">1. Filtros</h3>
                        <div id="filtros-container"></div>
                        <button id="add-filter-btn" class="px-4 py-2 mt-2 text-white bg-blue-500 rounded hover:bg-blue-600">Adicionar Filtro</button>
                    </div>

                    <div class="p-4 mb-6 border rounded-md">
                         <h3 class="mb-4 font-bold">2. Colunas a Exibir</h3>
                         <div id="colunas-container" class="grid grid-cols-2 gap-4 md:grid-cols-4"></div>
                    </div>

                    <div class="mb-6">
                        <button id="apply-filters-btn" class="w-full px-6 py-3 font-bold text-white bg-green-600 rounded-lg hover:bg-green-700">
                            GERAR RELATÓRIO
                        </button>
                    </div>

                    <div class="p-4 border rounded-md">
                        <h3 class="mb-2 font-bold">3. Resultados</h3>
                        <div id="loading-spinner" style="display: none;" class="flex items-center justify-center p-8 text-center">
                            <svg class="w-8 h-8 text-blue-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-0 ml-3">Buscando dados...</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead id="resultados-head"></thead>
                                <tbody id="resultados-body"></tbody>
                            </table>
                        </div>
                        <div id="pagination-links" class="mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- CONFIGURAÇÃO INICIAL E VARIÁVEIS DO BACKEND ---
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('ERRO CRÍTICO: Meta tag CSRF não encontrada.');
        return;
    }
    const csrfToken = csrfMeta.getAttribute('content');
    const filterUrl = '{{ route("admin.candidatos.relatorios.filtrar") }}';

    // Opções passadas pelo Controller do Laravel
    const cursoOptions = @json($cursos ?? []); // Espera [id => 'nome']
    const instituicaoOptions = @json($instituicoes ?? []); // Espera [id => 'nome']
    const statusOptions = { 'Inscrição Incompleta': 'Inscrição Incompleta', 'Aprovado': 'Aprovado', 'Rejeitado': 'Rejeitado', 'Homologado': 'Homologado', 'Convocado': 'Convocado' };

    // Definição dos campos para filtros e colunas
    const filterableFields = {
        'candidatos.status': { label: 'Status do Candidato', type: 'select', options: statusOptions },
        'candidatos.curso_id': { label: 'Curso', type: 'select', options: cursoOptions },
        'candidatos.instituicao_id': { label: 'Instituição', type: 'select', options: instituicaoOptions },
        'candidatos.pontuacao': { label: 'Pontuação (Banco)', type: 'number' },
        'candidatos.nome_completo': { label: 'Nome do Candidato', type: 'text' },
        'candidatos.created_at': { label: 'Data de Inscrição', type: 'daterange' },
        'candidatos.homologado_em': { label: 'Data de Homologação', type: 'daterange' },
        'candidatos.convocado_em': { label: 'Data de Convocação', type: 'daterange' }
    };
    const displayableColumns = {
        'nome_completo': 'Nome', 'cpf': 'CPF', 'email': 'Email', 'telefone': 'Telefone',
        'curso_nome': 'Curso', 'instituicao_nome': 'Instituição', 'status': 'Status', 'pontuacao_final': 'Pontuação'
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
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `coluna-${key}`;
            checkbox.name = 'colunas[]';
            checkbox.value = key;
            checkbox.className = 'column-checkbox rounded';
            checkbox.checked = defaultColumns.includes(key);

            const labelEl = document.createElement('label');
            labelEl.htmlFor = `coluna-${key}`;
            labelEl.className = 'ml-2';
            labelEl.textContent = label;

            div.append(checkbox, labelEl);
            colunasContainer.append(div);
        }
    }

    function addFilterRow() {
        const row = document.createElement('div');
        row.className = 'flex items-center space-x-2 mb-2 filter-row';

        // Dropdown de campos
        const fieldSelect = document.createElement('select');
        fieldSelect.name = 'field';
        fieldSelect.className = 'field-select border-gray-300 rounded-md shadow-sm';
        fieldSelect.innerHTML = `<option value="">Selecione um campo...</option>` +
            Object.entries(filterableFields).map(([key, val]) => `<option value="${key}">${val.label}</option>`).join('');

        // Dropdown de operadores (para números)
        const operatorSelect = document.createElement('select');
        operatorSelect.name = 'operator';
        operatorSelect.className = 'operator-select border-gray-300 rounded-md shadow-sm';
        operatorSelect.style.display = 'none';
        operatorSelect.innerHTML = `<option value="=">igual a</option><option value=">">maior que</option><option value="<">menor que</option>`;

        // Contêiner para o campo de valor
        const valueContainer = document.createElement('div');
        valueContainer.className = 'value-container flex-grow';

        // Botão de remover
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-filter-btn px-2 py-1 bg-red-500 text-white rounded text-sm';
        removeBtn.textContent = 'Remover';

        row.append(fieldSelect, operatorSelect, valueContainer, removeBtn);
        filtrosContainer.append(row);
    }

    function updateValueInput(selectElement) {
        const row = selectElement.closest('.filter-row');
        const valueContainer = row.querySelector('.value-container');
        const operatorSelect = row.querySelector('.operator-select');
        const selectedFieldKey = selectElement.value;

        valueContainer.innerHTML = ''; // Limpa o campo de valor anterior
        if (!selectedFieldKey) {
            operatorSelect.style.display = 'none';
            return;
        }

        const fieldConfig = filterableFields[selectedFieldKey];
        operatorSelect.style.display = fieldConfig.type === 'number' ? 'block' : 'none';

        if (fieldConfig.type === 'select') {
            const select = document.createElement('select');
            select.name = 'value';
            select.className = 'w-full border-gray-300 rounded-md shadow-sm';
            select.innerHTML = `<option value="">Selecione...</option>` +
                Object.entries(fieldConfig.options).map(([key, label]) => `<option value="${key}">${label}</option>`).join('');
            valueContainer.append(select);
        } else if (fieldConfig.type === 'daterange') {
            valueContainer.innerHTML = `<div class="flex items-center space-x-2">
                <input type="date" name="value_inicio" class="w-full border-gray-300 rounded-md shadow-sm">
                <span>até</span>
                <input type="date" name="value_fim" class="w-full border-gray-300 rounded-md shadow-sm">
            </div>`;
        } else {
            const input = document.createElement('input');
            input.type = fieldConfig.type;
            input.name = 'value';
            input.className = 'w-full border-gray-300 rounded-md shadow-sm';
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

        // Coleta as colunas e filtros selecionados
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
        }).filter(Boolean); // Remove filtros nulos

        // Monta o payload para o backend
        const payload = {
            colunas: selectedColumns, // Envia as colunas desejadas para o backend
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
            resultadosHead.innerHTML = `<tr>${selectedColumns.map(colKey => 
                `<th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">${displayableColumns[colKey] || colKey}</th>`
            ).join('')}</tr>`;

            // Renderiza o corpo da tabela
            if (result.data && result.data.length > 0) {
                let bodyHtml = '';
                result.data.forEach(item => {
                    bodyHtml += '<tr>';
                    selectedColumns.forEach(colKey => {
                        let value = item[colKey] ?? '';
                        bodyHtml += `<td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${value}</td>`;
                    });
                    bodyHtml += '</tr>';
                });
                resultadosBody.innerHTML = bodyHtml;
            } else {
                resultadosBody.innerHTML = `<tr><td colspan="${selectedColumns.length || 1}" class="p-4 text-center text-gray-500">Nenhum resultado encontrado.</td></tr>`;
            }

            // Renderiza a paginação
            if (result.links && result.links.length > 3) {
                const nav = document.createElement('nav');
                nav.className = 'flex items-center justify-between';
                const container = document.createElement('div');
                container.className = 'flex flex-wrap -mb-1';

                result.links.forEach(link => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    // Sanitiza o label para evitar problemas com HTML
                    button.innerHTML = new DOMParser().parseFromString(link.label, 'text/html').body.textContent;
                    button.disabled = !link.url;
                    button.className = `pagination-button relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium border leading-5 ${link.active ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`;
                    if(link.url) {
                        button.dataset.url = link.url; // Armazena a URL no data-attribute
                    }
                    container.append(button);
                });
                nav.append(container);
                paginationContainer.append(nav);
            }

        } catch (error) {
            console.error('Erro ao buscar dados:', error);
            resultadosBody.innerHTML = `<tr><td colspan="${selectedColumns.length || 1}" class="p-4 text-center text-red-500">Ocorreu um erro ao carregar os dados. Verifique o console para mais detalhes.</td></tr>`;
        } finally {
            loadingSpinner.style.display = 'none';
        }
    }
    
    // --- EVENT LISTENERS ---

    addFilterBtn.addEventListener('click', addFilterRow);
    applyFiltersBtn.addEventListener('click', () => fetchAndRenderResults());

    // Delegação de eventos para os botões de remover e selects de campo
    filtrosContainer.addEventListener('click', e => {
        if (e.target.classList.contains('remove-filter-btn')) {
            e.target.closest('.filter-row').remove();
        }
    });

    filtrosContainer.addEventListener('change', e => {
        if (e.target.classList.contains('field-select')) {
            updateValueInput(e.target);
        }
    });

    // Delegação de eventos para a paginação
    paginationContainer.addEventListener('click', e => {
        const button = e.target.closest('.pagination-button');
        if (button && !button.disabled && button.dataset.url) {
            fetchAndRenderResults(button.dataset.url);
        }
    });

    // --- INICIALIZAÇÃO DA PÁGINA ---
    populateColumnsSelector();
    addFilterRow();
});
</script>
</x-app-layout>