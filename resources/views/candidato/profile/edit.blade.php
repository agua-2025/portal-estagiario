<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- ✅ LÓGICA JAVASCRIPT CORRIGIDA E VARIÁVEIS DE STATUS --}}
                    <script>
                        function profileFormComponent(initialFields, estados, todasCidades, instituicoes, todosCursos, totalProfileFields, completableFields, candidatoStatus) {
                            return {
                                step: 1,
                                fields: initialFields,
                                percentage: 0,
                                validationAttempted: { step1: false, step2: false, step3: false },
                                cpfIsValid: true, // Adicionado: estado de validação do CPF
                                pickers: { nascimento: null, inicio: null, conclusao: null }, // [ADD] refs dos Flatpickrs

                                
                                estados: estados,
                                todasCidades: todasCidades,
                                instituicoes: instituicoes,
                                todosCursos: todosCursos,

                                cidadesNaturalidadeFiltradas: [],
                                cidadesEnderecoFiltradas: [],
                                cursosFiltrados: todosCursos,

                                candidatoStatus: candidatoStatus, // ✅ ADICIONADO: Status do candidato
                                
                                get isAguardandoHomologacao() { // ✅ ADICIONADO: Propriedade computada para status
                                    return this.candidatoStatus === 'Aprovado';
                                },

                                init() {
                                this.updateAllFilteredLists();
                                this.calculatePercentage();

                                // mantém seus watchers existentes
                                this.$watch('fields.naturalidade_estado', () => { 
                                    if (this.fields.naturalidade_estado) {
                                    const cidadesDoEstado = this.todasCidades.filter(c => c.estado_id == this.fields.naturalidade_estado);
                                    const cidadeAtualExiste = cidadesDoEstado.find(c => c.nome === this.fields.naturalidade_cidade);
                                    if (!cidadeAtualExiste) {
                                        this.fields.naturalidade_cidade = '';
                                    }
                                    }
                                    this.updateAllFilteredLists(); 
                                });

                                this.$watch('fields.estado', () => { 
                                    if (this.fields.estado) {
                                    const cidadesDoEstado = this.todasCidades.filter(c => c.estado_id == this.fields.estado);
                                    const cidadeAtualExiste = cidadesDoEstado.find(c => c.nome === this.fields.cidade);
                                    if (!cidadeAtualExiste) {
                                        this.fields.cidade = '';
                                    }
                                    }
                                    this.updateAllFilteredLists(); 
                                });

                                // valida CPF
                                this.$watch('fields.cpf', () => {
                                    this.cpfIsValid = this.validateCpf(this.fields.cpf);
                                });

                                // recalcula %
                                this.$watch('fields', () => this.calculatePercentage(), { deep: true });

                                // >>> CHAMADA QUE FALTAVA: inicializa os datepickers
                                this.$nextTick(() => { this.initDatePickers(); });
                                },


                                initDatePickers() {

                                // segurança: só roda se flatpickr estiver disponível (exposto no window em app.js)
                                if (!window.flatpickr) return;

                                // evita re-inicializar se Alpine remontar o componente
                                if (this.pickers.nascimento || this.pickers.inicio || this.pickers.conclusao) return;

                                const common = {
                                    dateFormat: "Y-m-d",  // valor REAL no input (vai para o backend)
                                    altInput: true,       // mostra campo amigável ao usuário
                                    altFormat: "d/m/Y",   // formato exibido ao usuário
                                    allowInput: true,     // permite digitar manualmente
                                    disableMobile: true   // força a UI do Flatpickr no celular
                                    // locale já foi setada globalmente em app.js (pt-BR)
                                };

                                // Data de nascimento — sem futuro
                                this.pickers.nascimento = flatpickr(
                                    this.$root.querySelector('#data_nascimento'),
                                    {
                                    ...common,
                                    defaultDate: this.fields.data_nascimento || null,
                                    maxDate: new Date(),
                                    // minDate: "1950-01-01", // opcional — defina se quiser
                                    onChange: (selectedDates, dateStr) => { this.fields.data_nascimento = dateStr; }
                                    }
                                );

                                // Início do curso — sem futuro
                                this.pickers.inicio = flatpickr(
                                    this.$root.querySelector('#curso_data_inicio'),
                                    {
                                    ...common,
                                    defaultDate: this.fields.curso_data_inicio || null,
                                    maxDate: new Date(),
                                    onChange: (selectedDates, dateStr) => {
                                        this.fields.curso_data_inicio = dateStr;
                                        // garante que conclusão >= início
                                        if (this.pickers.conclusao) {
                                        this.pickers.conclusao.set('minDate', dateStr || null);
                                        }
                                    }
                                    }
                                );

                                // Previsão de conclusão — >= início; máx opcional +10 anos
                                const maxConclusao = new Date();
                                maxConclusao.setFullYear(maxConclusao.getFullYear() + 10);

                                this.pickers.conclusao = flatpickr(
                                    this.$root.querySelector('#curso_previsao_conclusao'),
                                    {
                                    ...common,
                                    defaultDate: this.fields.curso_previsao_conclusao || null,
                                    minDate: this.fields.curso_data_inicio || null,
                                    maxDate: maxConclusao,
                                    onChange: (selectedDates, dateStr) => { this.fields.curso_previsao_conclusao = dateStr; }
                                    }
                                );
                                },


                              // Nova função para validar o CPF
                                validateCpf(cpf) {
                                    // Remove caracteres não numéricos
                                    const cleanedCpf = cpf.replace(/[^\d]+/g,'');
                                    
                                    // Retorna falso se a string for diferente de 11 dígitos ou for uma sequência inválida
                                    if (cleanedCpf.length !== 11 || /^(\d)\1{10}$/.test(cleanedCpf)) {
                                        return false;
                                    }

                                    let sum = 0;
                                    let rest;
                                    
                                    // Validação do primeiro dígito verificador
                                    for (let i = 1; i <= 9; i++) {
                                        sum = sum + parseInt(cleanedCpf.substring(i - 1, i)) * (11 - i);
                                    }
                                    rest = (sum * 10) % 11;

                                    if (rest === 10 || rest === 11) rest = 0;
                                    if (rest !== parseInt(cleanedCpf.substring(9, 10))) return false;

                                    sum = 0;
                                    
                                    // Validação do segundo dígito verificador
                                    for (let i = 1; i <= 10; i++) {
                                        sum = sum + parseInt(cleanedCpf.substring(i - 1, i)) * (12 - i);
                                    }
                                    rest = (sum * 10) % 11;

                                    if (rest === 10 || rest === 11) rest = 0;
                                    if (rest !== parseInt(cleanedCpf.substring(10, 11))) return false;

                                    return true;
                                },  

                                updateAllFilteredLists() {
                                    if (this.fields.naturalidade_estado) {
                                        this.cidadesNaturalidadeFiltradas = this.todasCidades.filter(c => c.estado_id == this.fields.naturalidade_estado);
                                    } else {
                                        this.cidadesNaturalidadeFiltradas = [];
                                    }

                                    if (this.fields.estado) {
                                        this.cidadesEnderecoFiltradas = this.todasCidades.filter(c => c.estado_id == this.fields.estado);
                                    } else {
                                        this.cidadesEnderecoFiltradas = [];
                                    }

                                    this.cursosFiltrados = this.todosCursos; 
                                },
                                
                                isStep1Valid() { 
                                    // A validação do CPF foi adicionada aqui
                                    return !!this.fields.nome_completo && 
                                        !!this.fields.nome_mae && 
                                        !!this.fields.data_nascimento && 
                                        !!this.fields.sexo && 
                                        !!this.fields.cpf && 
                                        this.cpfIsValid && // Adicionado: validação do CPF
                                        !!this.fields.naturalidade_estado && 
                                        !!this.fields.naturalidade_cidade && 
                                        (this.fields.possui_deficiencia !== null && this.fields.possui_deficiencia !== ''); 
                                },
                                
                                isStep2Valid() { 
                                    return !!this.fields.telefone && 
                                            !!this.fields.cep && 
                                            !!this.fields.logradouro && 
                                            !!this.fields.numero && 
                                            !!this.fields.bairro && 
                                            !!this.fields.estado && 
                                            !!this.fields.cidade; 
                                },

                                isStep3Valid() {
                                    return !!this.fields.instituicao_id && 
                                           !!this.fields.curso_id && 
                                           !!this.fields.curso_data_inicio &&
                                           !!this.fields.curso_previsao_conclusao &&
                                           !!this.fields.semestres_completos &&
                                           !!this.fields.media_aproveitamento;
                                },

                                attemptNextStep(currentStep) {
                                    if (this.isAguardandoHomologacao) return; // ✅ Impede avançar se aguardando homologação

                                    if (currentStep === 1) {
                                        this.validationAttempted.step1 = true;
                                        if (this.isStep1Valid()) { this.step++; }
                                    } else if (currentStep === 2) {
                                        this.validationAttempted.step2 = true;
                                        if (this.isStep2Valid()) { this.step++; }
                                    } else if (currentStep === 3) {
                                        this.validationAttempted.step3 = true;
                                    }
                                },

                                isInvalid(field, step) {
                                    if (step === 1 && !this.validationAttempted.step1) return false;
                                    if (step === 2 && !this.validationAttempted.step2) return false;
                                    if (step === 3 && !this.validationAttempted.step3) return false;
                                    
                                    const value = this.fields[field];
                                    if (field === 'possui_deficiencia') {
                                        return value === null || value === '';
                                    }
                                    
                                    // Validação do campo 'cpf'
                                    if (field === 'cpf') {
                                        // Retorna true se o campo estiver vazio ou se o CPF for inválido
                                        return !value || !this.cpfIsValid; 
                                    }

                                    return !value;
                                },

                                calculatePercentage() {
                                    let totalFields = totalProfileFields;
                                    if (totalFields === 0) { this.percentage = 0; return; }
                                    
                                    let filledFields = 0;
                                    completableFields.forEach(field => {
                                        const value = this.fields[field];
                                        if (field === 'possui_deficiencia') {
                                            if (value === '0' || value === '1' || value === 0 || value === 1) filledFields++;
                                        } else if (value !== null && value !== '') {
                                            filledFields++;
                                        }
                                    });
                                    
                                    this.percentage = Math.min(Math.round((filledFields / totalFields) * 100), 100);
                                }
                            }
                        }
                    </script>
                    @php
                        // Prepara os dados iniciais com o formato correto para o JavaScript
                        $initialData = $candidato->only($profileFields);
                        $initialData['data_nascimento'] = old('data_nascimento', optional($candidato->data_nascimento)->format('Y-m-d'));
                        $initialData['curso_data_inicio'] = old('curso_data_inicio', optional($candidato->curso_data_inicio)->format('Y-m-d'));
                        $initialData['curso_previsao_conclusao'] = old('curso_previsao_conclusao', optional($candidato->curso_previsao_conclusao)->format('Y-m-d'));
                        
                        $initialData['possui_deficiencia'] = old('possui_deficiencia', ($candidato->possui_deficiencia == 1) ? '1' : '0');
                        
                        // Garante que os IDs sejam strings para o Alpine.js
                        $initialData['naturalidade_estado'] = old('naturalidade_estado', (string) $candidato->naturalidade_estado);
                        $initialData['estado'] = old('estado', (string) $candidato->estado);
                        $initialData['instituicao_id'] = old('instituicao_id', $candidato->instituicao_id ? (string) $candidato->instituicao_id : '');
                        $initialData['curso_id'] = old('curso_id', $candidato->curso_id ? (string) $candidato->curso_id : '');
                        
                        // Garante que as cidades sejam strings também
                        $initialData['naturalidade_cidade'] = old('naturalidade_cidade', $candidato->naturalidade_cidade ?? '');
                        $initialData['cidade'] = old('cidade', $candidato->cidade ?? '');
                    @endphp

                    <div x-data="profileFormComponent(
                        @js($initialData),
                        @js($estados),
                        @js($cidades),
                        @js($instituicoes),
                        @js($cursos),
                        {{ $totalProfileFieldsCount }},
                        @js(\App\Models\Candidato::getCompletableFields()),
                        @js($candidato->status) {{-- ✅ PASSANDO O STATUS DO CANDIDATO PARA O JS --}}
                    )" x-init="init()">
                        
                        {{-- BARRA DE PROGRESSO E PASSOS --}}
                        <div class="mb-12 pb-4 border-b">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2 text-center">Meu Perfil de Candidato</h2>
                            <div class="w-full px-4 sm:px-0">
                                <p class="text-sm text-gray-600 text-center">Seu perfil está <strong x-text="percentage + '%'"></strong> completo.</p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" :style="`width: ${percentage}%`"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between text-center mb-8">
                            <div class="w-1/3">
                                <div class="mx-auto rounded-full flex justify-center items-center h-10 w-10 border-2" :class="step >= 1 ? 'border-blue-600' : 'border-gray-300'">1</div>
                                <div class="text-xs mt-2 uppercase" :class="step >= 1 ? 'text-blue-600' : 'text-gray-500'">Pessoais</div>
                            </div>
                            <div class="flex-auto border-t-2 mx-4" :class="step > 1 ? 'border-blue-600' : 'border-gray-300'"></div>
                            <div class="w-1/3">
                                <div class="mx-auto rounded-full flex justify-center items-center h-10 w-10 border-2" :class="step >= 2 ? 'border-blue-600' : 'border-gray-300'">2</div>
                                <div class="text-xs mt-2 uppercase" :class="step >= 2 ? 'text-blue-600' : 'text-gray-500'">Endereço</div>
                            </div>
                            <div class="flex-auto border-t-2 mx-4" :class="step > 2 ? 'border-blue-600' : 'border-gray-300'"></div>
                            <div class="w-1/3">
                                <div class="mx-auto rounded-full flex justify-center items-center h-10 w-10 border-2" :class="step >= 3 ? 'border-blue-600' : 'border-gray-300'">3</div>
                                <div class="text-xs mt-2 uppercase" :class="step >= 3 ? 'text-blue-600' : 'text-gray-500'">Acadêmico</div>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        
                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p class="font-bold">Opa! Algo deu errado.</p>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('candidato.profile.update') }}">
                            @csrf
                            @method('PUT')

                            {{-- ETAPA 1: DADOS PESSOAIS --}}
                            <div x-show="step === 1" x-transition>
                                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-t pt-6">Etapa 1: Dados Pessoais</h3>
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12">
                                        <label for="nome_completo" class="block font-medium text-sm text-gray-700">Nome Completo <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('nome_completo', 1) }" x-model="fields.nome_completo" id="nome_completo" name="nome_completo" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12">
                                        <label for="nome_mae" class="block font-medium text-sm text-gray-700">Nome da Mãe <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('nome_mae', 1) }" x-model="fields.nome_mae" id="nome_mae" name="nome_mae" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12">
                                        <label for="nome_pai" class="block font-medium text-sm text-gray-700">Nome do Pai</label>
                                        <input x-model="fields.nome_pai" id="nome_pai" name="nome_pai" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="data_nascimento" class="block font-medium text-sm text-gray-700">Data de Nascimento <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('data_nascimento', 1) }"
                                        x-model="fields.data_nascimento"
                                        id="data_nascimento" name="data_nascimento" type="text"
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300"
                                        required :disabled="isAguardandoHomologacao">
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="sexo" class="block font-medium text-sm text-gray-700">Sexo <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('sexo', 1) }" x-model="fields.sexo" id="sexo" name="sexo" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione...</option>
                                            <option value="Feminino">Feminino</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Outro">Outro</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="cpf" class="block font-medium text-sm text-gray-700">CPF <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('cpf', 1) }" x-mask="999.999.999-99" x-model="fields.cpf" id="cpf" name="cpf" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao">
                                        {{-- ✅ Adicionado: Mensagem de erro para CPF inválido --}}
                                        <p x-show="validationAttempted.step1 && !cpfIsValid" class="mt-2 text-sm text-red-600">Por favor, insira um CPF válido.</p>
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="rg" class="block font-medium text-sm text-gray-700">RG</label>
                                        <input x-model="fields.rg" id="rg" name="rg" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="rg_orgao_expedidor" class="block font-medium text-sm text-gray-700">Órgão Expedidor</label>
                                        <input x-model="fields.rg_orgao_expedidor" id="rg_orgao_expedidor" name="rg_orgao_expedidor" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    {{-- Campo possui_deficiencia --}}
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="possui_deficiencia" class="block font-medium text-sm text-gray-700">Possui Deficiência? <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('possui_deficiencia', 1) }" x-model="fields.possui_deficiencia" id="possui_deficiencia" name="possui_deficiencia" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione...</option>
                                            <option value="0">Não</option>
                                            <option value="1">Sim</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-4">
                                        <label for="naturalidade_estado" class="block font-medium text-sm text-gray-700">UF de Nascimento <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('naturalidade_estado', 1) }" x-model="fields.naturalidade_estado" name="naturalidade_estado" id="naturalidade_estado" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione</option>
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado->id }}">{{ $estado->uf }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    {{-- Campo naturalidade_cidade --}}
                                    <div class="col-span-12 sm:col-span-8">
                                        <label for="naturalidade_cidade" class="block font-medium text-sm text-gray-700">Cidade de Nascimento <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('naturalidade_cidade', 1) }" x-model="fields.naturalidade_cidade" name="naturalidade_cidade" id="naturalidade_cidade" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione a Cidade</option>
                                            <template x-for="cidade_item in cidadesNaturalidadeFiltradas" :key="cidade_item.id">
                                                <option :value="cidade_item.nome" x-text="cidade_item.nome" :selected="cidade_item.nome === fields.naturalidade_cidade"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- ETAPA 2: ENDEREÇO --}}
                            <div x-show="step === 2" x-transition>
                                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-t pt-6">Etapa 2: Contato e Endereço</h3>
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="telefone" class="block font-medium text-sm text-gray-700">Telefone <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('telefone', 2) }" x-mask="(99) 99999-9999" x-model="fields.telefone" id="telefone" name="telefone" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="cep" class="block font-medium text-sm text-gray-700">CEP <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('cep', 2) }" x-mask="99999-999" x-model="fields.cep" id="cep" name="cep" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-9">
                                        <label for="logradouro" class="block font-medium text-sm text-gray-700">Endereço (Rua, Av.) <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('logradouro', 2) }" x-model="fields.logradouro" id="logradouro" name="logradouro" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-3">
                                        <label for="numero" class="block font-medium text-sm text-gray-700">Número <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('numero', 2) }" x-model="fields.numero" id="numero" name="numero" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12">
                                        <label for="bairro" class="block font-medium text-sm text-gray-700">Bairro <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('bairro', 2) }" x-model="fields.bairro" id="bairro" name="bairro" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-4">
                                        <label for="estado" class="block font-medium text-sm text-gray-700">Estado <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('estado', 2) }" x-model="fields.estado" name="estado" id="estado" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione</option>
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado->id }}">{{ $estado->uf }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    {{-- Campo cidade --}}
                                    <div class="col-span-12 sm:col-span-8">
                                        <label for="cidade" class="block font-medium text-sm text-gray-700">Cidade <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('cidade', 2) }" x-model="fields.cidade" name="cidade" id="cidade" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione a Cidade</option>
                                            <template x-for="cidade_item in cidadesEnderecoFiltradas" :key="cidade_item.id">
                                                <option :value="cidade_item.nome" x-text="cidade_item.nome" :selected="cidade_item.nome === fields.cidade"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- ETAPA 3: DADOS ACADÊMICOS --}}
                            <div x-show="step === 3" x-transition>
                                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-t pt-6">Etapa 3: Dados Acadêmicos</h3>
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12">
                                        <label for="instituicao_id" class="block font-medium text-sm text-gray-700">Instituição de Ensino <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('instituicao_id', 3) }" x-model="fields.instituicao_id" name="instituicao_id" id="instituicao_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione...</option>
                                            @foreach ($instituicoes as $instituicao)
                                                <option value="{{ $instituicao->id }}">{{ $instituicao->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    {{-- Campo curso_id --}}
                                    <div class="col-span-12">
                                        <label for="curso_id" class="block font-medium text-sm text-gray-700">Curso de Graduação <span class="text-red-500">*</span></label>
                                        <select :class="{ 'border-red-500': isInvalid('curso_id', 3) }" x-model="fields.curso_id" name="curso_id" id="curso_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                            <option value="">Selecione o Curso</option>
                                            <template x-for="curso_item in todosCursos" :key="curso_item.id">
                                                <option :value="curso_item.id" x-text="curso_item.nome" :selected="curso_item.id === parseInt(fields.curso_id)"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="curso_data_inicio" class="block font-medium text-sm text-gray-700">Data de Início do Curso <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('curso_data_inicio', 3) }"
                                        x-model="fields.curso_data_inicio"
                                        id="curso_data_inicio" name="curso_data_inicio" type="text"
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300"
                                        required :disabled="isAguardandoHomologacao">
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="curso_previsao_conclusao" class="block font-medium text-sm text-gray-700">Previsão de Conclusão <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('curso_previsao_conclusao', 3) }"
                                        x-model="fields.curso_previsao_conclusao"
                                        id="curso_previsao_conclusao" name="curso_previsao_conclusao" type="text"
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300"
                                        required :disabled="isAguardandoHomologacao">
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="semestres_completos" class="block font-medium text-sm text-gray-700">Semestres Concluídos <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('semestres_completos', 3) }" x-model="fields.semestres_completos" name="semestres_completos" type="number" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                    
                                    <div class="col-span-12 sm:col-span-6">
                                        <label for="media_aproveitamento" class="block font-medium text-sm text-gray-700">Média de Aproveitamento <span class="text-red-500">*</span></label>
                                        <input :class="{ 'border-red-500': isInvalid('media_aproveitamento', 3) }" x-model="fields.media_aproveitamento" name="media_aproveitamento" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" required :disabled="isAguardandoHomologacao"> {{-- ✅ DESABILITADO --}}
                                    </div>
                                </div>
                            </div>
                            
                            {{-- BOTÕES DE NAVEGAÇÃO E SALVAR --}}
                            <div class="flex items-center justify-between mt-6 pt-6 border-t">
                                <button type="button" x-show="step > 1" @click.prevent="step--" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Anterior</button>
                                <div x-show="step === 1"></div> {{-- Espaçador --}}
                                
                                <button type="button" x-show="step === 1" @click.prevent="attemptNextStep(1)" :disabled="isAguardandoHomologacao" class="px-4 py-2 text-white rounded-lg bg-blue-600 hover:bg-blue-700">Próximo</button> {{-- ✅ DESABILITADO --}}
                                <button type="button" x-show="step === 2" @click.prevent="attemptNextStep(2)" :disabled="isAguardandoHomologacao" class="px-4 py-2 text-white rounded-lg bg-blue-600 hover:bg-blue-700">Próximo</button> {{-- ✅ DESABILITADO --}}
                                
                                <button type="submit" x-show="step === 3" :disabled="isAguardandoHomologacao" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Salvar Perfil</button> {{-- ✅ DESABILITADO --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>