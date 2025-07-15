<x-app-layout>
    <div class="py-6"> 
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- ✅ AJUSTE CRÍTICO E DEFINITIVO: Prepara TODAS as variáveis em um único array PHP para json_encode --}}
                @php
                    // Garante que o ID da regra selecionada seja sempre uma string, usando dados da atividade existente
                    $initialRegraSelecionada = (string) (old('tipo_de_atividade_id') ?? $atividade->tipo_de_atividade_id ?? '');
                    // Garante que $regrasDePontuacao seja um array para json_encode
                    $regrasDePontuacaoArray = $regrasDePontuacao->toArray();
                    
                    // Prepara os campos do 'fields' para garantir que sejam strings ou valores válidos
                    $fieldsData = [
                        'descricao_customizada' => (string) (old('descricao_customizada') ?? $atividade->descricao_customizada ?? ''),
                        'carga_horaria' => (string) (old('carga_horaria') ?? $atividade->carga_horaria ?? ''),
                        'data_inicio' => (string) (old('data_inicio') ?? ($atividade->data_inicio ? $atividade->data_inicio->format('Y-m-d') : '') ?? ''),
                        'data_fim' => (string) (old('data_fim') ?? ($atividade->data_fim ? $atividade->data_fim->format('Y-m-d') : '') ?? ''),
                        'semestres_declarados' => (string) (old('semestres_declarados') ?? $atividade->semestres_declarados ?? ''),
                        'media_declarada_atividade' => (string) (old('media_declarada_atividade') ?? $atividade->media_declarada_atividade ?? ''),
                    ];

                    // Cria um único array com todos os dados que o Alpine.js precisa
                    $alpineData = [
                        'showForm' => true, // Sempre mostra o formulário na página de edição
                        'regraSelecionada' => $initialRegraSelecionada,
                        'regras' => $regrasDePontuacaoArray,
                        'fields' => $fieldsData,
                        'validationAttempted' => false,
                        'isEditing' => true,
                    ];
                @endphp

                <div class="p-4 text-gray-900" x-data="alpineFormData()" x-init="initializeForm()">

                    <div class="mb-4 border-b pb-3">
                        <h2 class="text-xl font-semibold text-gray-800">Editar Item de Pontuação</h2>
                        <p class="mt-1 text-sm text-gray-600">Modifique as informações do item selecionado.</p>
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

                    <div class="border-2 border-dashed rounded-lg p-3 my-3">
                        <form x-ref="activityEditForm" action="{{ route('candidato.atividades.update', $atividade) }}" method="POST" enctype="multipart/form-data" @submit.prevent="attemptSave()">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-2"> 
                                <div>
                                    <label for="tipo_de_atividade_id" class="block text-sm font-medium text-gray-700">Qual item de pontuação você quer editar?</label>
                                    <select name="tipo_de_atividade_id" x-model="regraSelecionada" class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm" 
                                            :class="{'border-red-500': isFieldInvalid('tipo_de_atividade_id')}" required>
                                        <option value="">Selecione...</option>
                                        <template x-for="regra in regras" :key="regra.id">
                                            <option :value="regra.id" x-text="regra.nome" :selected="regra.id == regraSelecionada"></option>
                                        </template>
                                    </select>
                                </div>
                                
                                <div x-show="regraSelecionada && !isSemestresCursadosRule()">
                                    <label for="descricao_customizada" class="block text-sm font-medium text-gray-700" x-text="descricaoLabel"></label>
                                    <input type="text" name="descricao_customizada" x-model="fields.descricao_customizada" 
                                           class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm" 
                                           :class="{'border-red-500': isFieldInvalid('descricao_customizada')}" 
                                           :required="!isSemestresCursadosRule()" 
                                           :placeholder="isSemestresCursadosRule() ? 'Ex: Semestre atual, nome da disciplina, etc.' : (isAproveitamentoAcademicoRule() ? 'Ex: Histórico escolar, certificado de curso, etc.' : '')">
                                </div>

                                {{-- Semestres Declarados --}}
                                <div x-show="isSemestresCursadosRule()" class="sm:w-1/2">
                                    <label for="semestres_declarados" class="block text-sm font-medium text-gray-700">Número de Semestres Declarados</label>
                                    <input type="number" name="semestres_declarados" x-model="fields.semestres_declarados" 
                                           class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm" 
                                           :class="{'border-red-500': isFieldInvalid('semestres_declarados')}" 
                                           :required="isSemestresCursadosRule()" min="1">
                                </div>

                                {{-- Média Declarada na Atividade --}}
                                <div x-show="isAproveitamentoAcademicoRule()" class="sm:w-1/2">
                                    <label for="media_declarada_atividade" class="block text-sm font-medium text-gray-700">Média de Aproveitamento</label>
                                    <input type="number" step="0.01" name="media_declarada_atividade" x-model="fields.media_declarada_atividade" 
                                           class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm" 
                                           :class="{'border-red-500': isFieldInvalid('media_declarada_atividade')}" 
                                           :required="isAproveitamentoAcademicoRule()" min="0" max="10">
                                </div>

                                {{-- Carga Horária --}}
                                <div x-show="regraSelecionada && selectedRegra && selectedRegra.unidade_medida === 'horas' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                    <label for="carga_horaria" class="block text-sm font-medium text-gray-700">Carga Horária Total</label>
                                    <input type="number" name="carga_horaria" x-model="fields.carga_horaria" 
                                           class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm" 
                                           :class="{'border-red-500': isFieldInvalid('carga_horaria')}"
                                           :required="selectedRegra && selectedRegra.unidade_medida === 'horas' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()"
                                           min="1">
                                </div>

                                {{-- Datas --}}
                                <div x-show="regraSelecionada && selectedRegra && selectedRegra.unidade_medida === 'meses' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data de Início</label>
                                            <input type="date" name="data_inicio" x-model="fields.data_inicio" 
                                                   class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm" 
                                                   :class="{'border-red-500': isFieldInvalid('data_inicio')}"
                                                   :required="selectedRegra && selectedRegra.unidade_medida === 'meses' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                        </div>
                                        <div>
                                            <label for="data_fim" class="block text-sm font-medium text-gray-700">Data de Fim</label>
                                            <input type="date" name="data_fim" x-model="fields.data_fim" 
                                                   class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm" 
                                                   :class="{'border-red-500': isFieldInvalid('data_fim')}"
                                                   :required="selectedRegra && selectedRegra.unidade_medida === 'meses' && !isSemestresCursadosRule() && !isAproveitamentoAcademicoRule()">
                                        </div>
                                    </div>
                                </div>

                                <div x-show="regraSelecionada">
                                <label for="comprovativo" class="block text-sm font-medium text-gray-700">
                                    Anexar Novo Comprovativo 
                                    <span class="text-gray-500 text-xs">(deixe em branco para manter o arquivo atual)</span>
                                </label>
                                <input type="file" name="comprovativo" id="comprovativo" class="mt-0.5 block w-full text-sm" 
                                    :class="{'border-red-500': isFieldInvalid('comprovativo')}">
                                
                                {{-- ✅ ✅ ✅  COPIE E COLE ESTE BLOCO ABAIXO  ✅ ✅ ✅ --}}
                                @if($atividade->path)  {{-- Usando a propriedade correta do seu Controller --}}
                                    <div class="mt-2 text-sm">
                                        <span class="text-gray-700 font-medium">Arquivo atual:</span>
                                        <a href="{{ Storage::url($atividade->path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                            {{-- Mostra apenas o nome do arquivo, e não o caminho completo --}}
                                            {{ basename($atividade->path) }}
                                        </a>
                                    </div>
                                @endif
                                </div>
                            </div>
                            
                            <div class="flex justify-end items-center mt-3 space-x-3">
                                <a href="{{ route('candidato.atividades.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                                    Cancelar
                                </a>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm" 
                                        :disabled="validationAttempted && !isFormValid()"> 
                                    Atualizar Item
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Informações sobre o status atual --}}
                    <div class="mt-6 pt-4 border-t">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Status do Item</h3>
                        <div class="p-3 border rounded-lg">
                            @php
                                $statusClass = 'bg-gray-100 text-gray-800';
                                if ($atividade->status === 'Aprovada') $statusClass = 'bg-green-100 text-green-800';
                                elseif ($atividade->status === 'Rejeitada') $statusClass = 'bg-red-100 text-red-800';
                                elseif ($atividade->status === 'enviado') $statusClass = 'bg-blue-100 text-blue-800';
                                elseif ($atividade->status === 'Em Análise') $statusClass = 'bg-purple-100 text-purple-800';
                            @endphp
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">{{ $atividade->tipoDeAtividade->nome }}</p>
                                    <p class="text-sm text-gray-600">{{ $atividade->descricao_customizada }}</p>
                                </div>
                                <span class="font-medium capitalize px-3 py-1 rounded-full text-sm {{ $statusClass }}">
                                    {{ $atividade->status }}
                                </span>
                            </div>
                            
                            @if($atividade->status === 'Rejeitada' && $atividade->motivo_rejeicao)
                                <div class="mt-3 p-3 text-sm text-red-800 bg-red-50 rounded-md border border-red-200">
                                    <strong class="font-bold">Motivo da Rejeição:</strong> {{ $atividade->motivo_rejeicao }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function alpineFormData() {
            return {
                // Dados iniciais do PHP
                ...@json($alpineData),
                
                // Computed properties
                get selectedRegra() {
                    const selectedRegraId = parseInt(this.regraSelecionada);
                    if (isNaN(selectedRegraId) || selectedRegraId === 0) return null; 
                    return this.regras.find(r => r.id === selectedRegraId);
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
                
                // Métodos de verificação
                isSemestresCursadosRule() {
                    if (!this.selectedRegra) return false; 
                    const nomeRegra = this.selectedRegra.nome.toLowerCase();
                    return nomeRegra.includes('semestres cursados');
                },
                
                isAproveitamentoAcademicoRule() { 
                    if (!this.selectedRegra) return false;
                    const nomeRegra = this.selectedRegra.nome.toLowerCase();
                    return nomeRegra.includes('aproveitamento acadêmico');
                },
                
                // Validações
                isFormValid() {
                    if (!this.regraSelecionada) return false;
                    
                    // Validação específica para semestres cursados
                    if (this.isSemestresCursadosRule()) {
                        const semestres = parseInt(this.fields.semestres_declarados);
                        return !isNaN(semestres) && semestres >= 1;
                    }
                    
                    // Validação específica para aproveitamento acadêmico
                    if (this.isAproveitamentoAcademicoRule()) {
                        const media = parseFloat(this.fields.media_declarada_atividade);
                        return !isNaN(media) && media >= 0 && media <= 10;
                    }
                    
                    // Validação de descrição para outros casos
                    if (!this.fields.descricao_customizada.trim()) return false;
                    
                    // Validação de carga horária
                    if (this.selectedRegra && this.selectedRegra.unidade_medida === 'horas') {
                        const cargaHoraria = parseInt(this.fields.carga_horaria);
                        if (isNaN(cargaHoraria) || cargaHoraria < 1) return false;
                    }
                    
                    // Validação de datas
                    if (this.selectedRegra && this.selectedRegra.unidade_medida === 'meses') {
                        if (!this.fields.data_inicio || !this.fields.data_fim) return false;
                    }
                    
                    // Para edição, arquivo não é obrigatório
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
                            if (!value) return true; // Campo obrigatório
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
                            // Na edição, arquivo não é obrigatório
                            return false;
                            
                        default:
                            return false;
                    }
                },
                
                // Métodos de ação
                initializeForm() {
                    // Força a conversão para string para compatibilidade com o select
                    this.regraSelecionada = String(this.regraSelecionada || '');
                    
                    // Debug para verificar os valores
                    console.log('Regra selecionada inicial:', this.regraSelecionada, typeof this.regraSelecionada);
                    console.log('Regras disponíveis:', this.regras.map(r => ({ id: r.id, tipo: typeof r.id, nome: r.nome })));
                    
                    // Aguarda um tick para garantir que o DOM está atualizado
                    this.$nextTick(() => {
                        // Limpar campos desnecessários baseado na regra inicial
                        this.clearUnusedFields();
                    });
                    
                    // Watch para mudanças na regra selecionada
                    this.$watch('regraSelecionada', () => {
                        this.clearUnusedFields();
                    });
                },
                
                clearUnusedFields() {
                    // Limpar semestres se não for regra de semestres cursados
                    if (!this.isSemestresCursadosRule()) {
                        this.fields.semestres_declarados = '';
                    }
                    
                    // Limpar média se não for aproveitamento acadêmico
                    if (!this.isAproveitamentoAcademicoRule()) {
                        this.fields.media_declarada_atividade = '';
                    }
                    
                    // Limpar carga horária se não for regra de horas
                    if (!this.selectedRegra || this.selectedRegra.unidade_medida !== 'horas') {
                        this.fields.carga_horaria = '';
                    }
                    
                    // Limpar datas se não for regra de meses
                    if (!this.selectedRegra || this.selectedRegra.unidade_medida !== 'meses') {
                        this.fields.data_inicio = '';
                        this.fields.data_fim = '';
                    }
                },
                
                attemptSave() {
                    this.validationAttempted = true;
                    
                    if (this.isFormValid()) {
                        this.$refs.activityEditForm.submit();
                    } else {
                        // Focar no primeiro campo inválido
                        setTimeout(() => {
                            const form = this.$refs.activityEditForm;
                            if (form) {
                                const firstInvalidField = form.querySelector(':invalid, .border-red-500');
                                if (firstInvalidField) {
                                    firstInvalidField.focus();
                                }
                            }
                        }, 100);
                    }
                }
            }
        }
    </script>
</x-app-layout>