<x-app-layout>
    {{-- ✅ AJUSTE 1: Contêiner principal para limitar a largura e centralizar a página --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @php
                    $initialRegraSelecionada = (string) (old('tipo_de_atividade_id') ?? '');
                    $regrasDePontuacaoArray = $regrasDePontuacao->toArray();
                    
                    $fieldsData = [
                        'descricao_customizada' => (string) (old('descricao_customizada') ?? ''),
                        'carga_horaria' => (string) (old('carga_horaria') ?? ''),
                        'data_inicio' => (string) (old('data_inicio') ?? ''),
                        'data_fim' => (string) (old('data_fim') ?? ''),
                        'semestres_declarados' => (string) (old('semestres_declarados') ?? ''),
                        'media_declarada_atividade' => (string) (old('media_declarada_atividade') ?? ''),
                    ];

                    $alpineData = [
                        'showForm' => !! $errors->any(),
                        'regraSelecionada' => $initialRegraSelecionada,
                        'regras' => $regrasDePontuacaoArray,
                        'fields' => $fieldsData,
                        'validationAttempted' => false,
                    ];
                @endphp

                <div class="p-6 text-gray-900" x-data="alpineFormData()" x-init="initializeForm()">

                    <div class="mb-4 border-b pb-3">
                        <h2 class="text-xl font-semibold text-gray-800">Anexar Itens para Pontuação</h2>
                        <p class="mt-1 text-sm text-gray-600">Adicione aqui os itens que somam pontos na sua classificação.</p>
                    </div>
                    
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Opa! Algo deu errado.</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-center py-2 border-2 border-dashed rounded-lg">
    <button @click="showForm = !showForm" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">
        <span x-show="!showForm">[+] Adicionar Novo Item</span>
        <span x-show="showForm">[-] Ocultar Formulário</span>
    </button>
</div>

                    <div x-show="showForm" x-transition class="border-2 border-dashed rounded-lg p-4 my-4">
                        <form x-ref="activityAddForm" action="{{ route('candidato.atividades.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="attemptSave()">
                            @csrf
                            <div class="space-y-4"> 
                                <div>
                                    <label for="tipo_de_atividade_id" class="block text-sm font-medium text-gray-700">Qual item de pontuação você quer adicionar?</label>
                                    <select name="tipo_de_atividade_id" x-model="regraSelecionada" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                            :class="{'border-red-500': isFieldInvalid('tipo_de_atividade_id')}" required>
                                        <option value="">Selecione...</option>
                                        <template x-for="regra in regras" :key="regra.id">
                                            <option :value="regra.id" x-text="regra.nome"></option>
                                        </template>
                                    </select>
                                </div>
                                
                                <div x-show="regraSelecionada && !isSemestresCursadosRule()">
                                    <label for="descricao_customizada" class="block text-sm font-medium text-gray-700" x-text="descricaoLabel"></label>
                                    <input type="text" name="descricao_customizada" x-model="fields.descricao_customizada" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           :class="{'border-red-500': isFieldInvalid('descricao_customizada')}" 
                                           :required="!isSemestresCursadosRule()" 
                                           :placeholder="isSemestresCursadosRule() ? 'Ex: Semestre atual, nome da disciplina, etc.' : (isAproveitamentoAcademicoRule() ? 'Ex: Histórico escolar, certificado de curso, etc.' : '')">
                                </div>

                                {{-- Semestres Declarados --}}
                                <div x-show="isSemestresCursadosRule()" class="sm:w-1/2">
                                    <label for="semestres_declarados" class="block text-sm font-medium text-gray-700">Número de Semestres Declarados</label>
                                    <input type="number" name="semestres_declarados" x-model="fields.semestres_declarados" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           :class="{'border-red-500': isFieldInvalid('semestres_declarados')}" 
                                           :required="isSemestresCursadosRule()" min="1">
                                </div>

                                {{-- Média Declarada na Atividade --}}
                                <div x-show="isAproveitamentoAcademicoRule()" class="sm:w-1/2">
                                    <label for="media_declarada_atividade" class="block text-sm font-medium text-gray-700">Média de Aproveitamento</label>
                                    <input type="number" step="0.01" name="media_declarada_atividade" x-model="fields.media_declarada_atividade" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           :class="{'border-red-500': isFieldInvalid('media_declarada_atividade')}" 
                                           :required="isAproveitamentoAcademicoRule()" min="0" max="10">
                                </div>

                                {{-- Carga Horária --}}
                                <div x-show="regraSelecionada && selectedRegra && selectedRegra.unidade_medida === 'horas' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                    <label for="carga_horaria" class="block text-sm font-medium text-gray-700">Carga Horária Total</label>
                                    <input type="number" name="carga_horaria" x-model="fields.carga_horaria" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           :class="{'border-red-500': isFieldInvalid('carga_horaria')}"
                                           :required="selectedRegra && selectedRegra.unidade_medida === 'horas' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()"
                                           min="1">
                                </div>

                                {{-- Datas --}}
                                <div x-show="regraSelecionada && selectedRegra && selectedRegra.unidade_medida === 'meses' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data de Início</label>
                                            <input type="date" name="data_inicio" x-model="fields.data_inicio" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                   :class="{'border-red-500': isFieldInvalid('data_inicio')}"
                                                   :required="selectedRegra && selectedRegra.unidade_medida === 'meses' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                        </div>
                                        <div>
                                            <label for="data_fim" class="block text-sm font-medium text-gray-700">Data de Fim</label>
                                            <input type="date" name="data_fim" x-model="fields.data_fim" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                   :class="{'border-red-500': isFieldInvalid('data_fim')}"
                                                   :required="selectedRegra && selectedRegra.unidade_medida === 'meses' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                        </div>
                                    </div>
                                </div>

                                <div x-show="regraSelecionada">
                                    <label for="comprovativo" class="block text-sm font-medium text-gray-700">Anexar Comprovativo (PDF, JPG, PNG)</label>
                                    <input type="file" name="comprovativo" id="comprovativo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                           :class="{'border-red-500 ring-red-500 ring-1 rounded-lg': isFieldInvalid('comprovativo')}" required>
                                </div>
                            </div>
                            <div class="flex justify-end items-center mt-6 space-x-4">
                                <button type="button" @click="showForm = false" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-semibold" 
                                        :disabled="validationAttempted && !isFormValid()"> 
                                    Salvar Item
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-8 pt-6 border-t">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Meus Itens Enviados</h3>
                        <div class="space-y-3">
                            @forelse ($atividadesEnviadas as $atividade)
                                <div class="p-3 border rounded-lg flex justify-between items-start text-sm">
                                    <div class="flex-grow">
                                        <p class="font-semibold text-gray-800">{{ $atividade->tipoDeAtividade->nome }}</p>
                                        <p class="text-xs text-gray-600">{{ $atividade->descricao_customizada }}</p>
                                        
                                        @if($atividade->status === 'Rejeitada')
                                            {{-- ✅ AJUSTE 2: Adicionada a classe "break-all" para forçar a quebra de linha do texto --}}
                                            <div class="mt-2 p-2 text-xs text-red-800 bg-red-50 rounded-md border border-red-200 break-all">
                                                <strong class="font-bold">Motivo da Rejeição:</strong> {{ $atividade->motivo_rejeicao }}
                                                
                                                @if($atividade->prazo_recurso_ate)
                                                    @if(\Carbon\Carbon::now()->lt($atividade->prazo_recurso_ate))
                                                        <p class="mt-1 font-bold">
                                                            Você pode corrigir e reenviar este item até: 
                                                            {{ \Carbon\Carbon::parse($atividade->prazo_recurso_ate)->format('d/m/Y \à\s H:i') }}
                                                        </p>
                                                    @else
                                                        <p class="mt-1 font-bold text-red-600">O prazo para recurso deste item encerrou.</p>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                    
                                    <div class="flex items-center space-x-3 flex-shrink-0 ml-4">
                                        @php
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            if ($atividade->status === 'Aprovada') $statusClass = 'bg-green-100 text-green-800';
                                            elseif ($atividade->status === 'Rejeitada') $statusClass = 'bg-red-100 text-red-800';
                                            elseif ($atividade->status === 'enviado') $statusClass = 'bg-blue-100 text-blue-800';
                                            elseif ($atividade->status === 'Em Análise') $statusClass = 'bg-purple-100 text-purple-800';
                                        @endphp
                                        <span class="font-medium capitalize px-2 py-1 rounded-full text-xs {{ $statusClass }}">{{ $atividade->status }}</span>
                                        <a href="{{ route('candidato.atividades.edit', $atividade) }}" class="px-3 py-1 bg-gray-200 text-gray-800 rounded-md text-xs hover:bg-gray-300">Editar</a>
                                        
                                        @can('delete', $atividade)
                                            <form method="POST" action="{{ route('candidato.atividades.destroy', $atividade) }}" onsubmit="return confirm('Tem a certeza que deseja excluir este item? Esta ação não pode ser desfeita.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded-md text-xs hover:bg-red-200">Excluir</button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="text-center border-2 border-dashed rounded-lg py-6">
                                    <p class="text-sm text-gray-500">Nenhum item para pontuação adicionado.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        function alpineFormData() {
            return {
                ...@json($alpineData),
                
                get selectedRegra() {
                    if (!this.regraSelecionada) return null;
                    return this.regras.find(r => r.id == this.regraSelecionada);
                },
                
                get descricaoLabel() {
                    if (!this.selectedRegra) return 'Descrição';
                    const nomeRegra = this.selectedRegra.nome.toLowerCase();
                    if (nomeRegra.includes('curso')) return 'Nome do Curso';
                    if (nomeRegra.includes('experiência')) return 'Nome da Empresa';
                    if (nomeRegra.includes('monitoria') || nomeRegra.includes('voluntário')) return 'Nome do Projeto / Local';
                    if (nomeRegra.includes('semestres cursados')) return 'Informações Adicionais (Opcional)';
                    if (nomeRegra.includes('aproveitamento acadêmico')) return 'Descrição do Aproveitamento';
                    return 'Descrição / Nome Específico';
                },
                
                isSemestresCursadosRule() {
                    if (!this.selectedRegra) return false; 
                    return this.selectedRegra.nome.toLowerCase().includes('semestres cursados');
                },
                
                isAproveitamentoAcademicoRule() { 
                    if (!this.selectedRegra) return false;
                    return this.selectedRegra.nome.toLowerCase().includes('aproveitamento acadêmico');
                },
                
                isFormValid() {
                    if (!this.regraSelecionada) return false;
                    
                    if (this.isSemestresCursadosRule()) {
                        const semestres = parseInt(this.fields.semestres_declarados);
                        return !isNaN(semestres) && semestres >= 1;
                    }
                    
                    if (this.isAproveitamentoAcademicoRule()) {
                        const media = parseFloat(this.fields.media_declarada_atividade);
                        return !isNaN(media) && media >= 0 && media <= 10;
                    }
                    
                    if (!this.fields.descricao_customizada.trim()) return false;
                    
                    if (this.selectedRegra && this.selectedRegra.unidade_medida === 'horas') {
                        const cargaHoraria = parseInt(this.fields.carga_horaria);
                        if (isNaN(cargaHoraria) || cargaHoraria < 1) return false;
                    }
                    
                    if (this.selectedRegra && this.selectedRegra.unidade_medida === 'meses') {
                        if (!this.fields.data_inicio || !this.fields.data_fim) return false;
                    }
                    
                    const comprovativoInput = document.getElementById('comprovativo'); 
                    if (comprovativoInput && comprovativoInput.required && !comprovativoInput.files.length) { 
                        return false; 
                    }
                    
                    return true;
                },
                
                isFieldInvalid(field) {
                    if (!this.validationAttempted) return false; 
                    const value = this.fields[field]; 

                    switch (field) {
                        case 'tipo_de_atividade_id':
                            return !this.regraSelecionada;
                            
                        case 'descricao_customizada':
                            return !this.isSemestresCursadosRule() && !value.trim();
                            
                        case 'semestres_declarados':
                            if (!this.isSemestresCursadosRule()) return false;
                            const semestres = parseInt(value);
                            return isNaN(semestres) || semestres < 1;
                            
                        case 'media_declarada_atividade':
                            if (!this.isAproveitamentoAcademicoRule()) return false;
                            if (!value) return true;
                            const media = parseFloat(value);
                            return isNaN(media) || media < 0 || media > 10;
                            
                        case 'carga_horaria':
                            if (!this.selectedRegra || this.selectedRegra.unidade_medida !== 'horas' || this.isSemestresCursadosRule() || this.isAproveitamentoAcademicoRule()) return false;
                            const carga = parseInt(value);
                            return isNaN(carga) || carga < 1;
                            
                        case 'data_inicio':
                        case 'data_fim':
                            if (!this.selectedRegra || this.selectedRegra.unidade_medida !== 'meses' || this.isSemestresCursadosRule() || this.isAproveitamentoAcademicoRule()) return false;
                            return !value;
                            
                        case 'comprovativo':
                            const fileInput = document.getElementById('comprovativo');
                            return fileInput && fileInput.required && !fileInput.files.length;
                            
                        default:
                            return false;
                    }
                },
                
                initializeForm() {
                    if (this.regraSelecionada) {
                        this.regraSelecionada = parseInt(this.regraSelecionada); 
                        if (isNaN(this.regraSelecionada)) {
                            this.regraSelecionada = '';
                        }
                    }
                    
                    this.clearUnusedFields();
                    
                    this.$watch('regraSelecionada', () => {
                        this.clearUnusedFields();
                    });
                },
                
                clearUnusedFields() {
                    if (!this.isSemestresCursadosRule()) {
                        this.fields.semestres_declarados = '';
                    }
                    
                    if (!this.isAproveitamentoAcademicoRule()) {
                        this.fields.media_declarada_atividade = '';
                    }
                    
                    if (!this.selectedRegra || this.selectedRegra.unidade_medida !== 'horas') {
                        this.fields.carga_horaria = '';
                    }
                    
                    if (!this.selectedRegra || this.selectedRegra.unidade_medida !== 'meses') {
                        this.fields.data_inicio = '';
                        this.fields.data_fim = '';
                    }
                },
                
                attemptSave() {
                    this.validationAttempted = true;
                    
                    this.$nextTick(() => {
                        if (this.isFormValid()) {
                            this.$refs.activityAddForm.submit();
                        } else {
                            const firstInvalidField = this.$el.querySelector('[name]:invalid, .border-red-500');
                            if (firstInvalidField) {
                                firstInvalidField.focus();
                                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>