<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Formulário de Convocação -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 md:p-8">
                    <!-- Título do Formulário -->
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Convocar Candidato</h3>
                                <p class="text-lg text-gray-800 font-medium">{{ $candidato->nome_completo }}</p>
                               </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.candidatos.convocar', $candidato) }}" method="POST">
                        @csrf
                        
                        <!-- Seção: Dados de Lotação -->
                        <div class="mb-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Dados de Lotação
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label for="lotacao_local" class="block text-sm font-medium text-gray-700 mb-2">
                                        Local de Lotação *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="lotacao_local" 
                                        id="lotacao_local" 
                                        value="{{ old('lotacao_local') }}" 
                                        required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ex: Secretaria Municipal de Educação"
                                    >
                                </div>

                                <div>
                                    <label for="lotacao_chefia" class="block text-sm font-medium text-gray-700 mb-2">
                                        Chefia Imediata *
                                    </label>
                                    <input 
                                        type="text" 
                                        name="lotacao_chefia" 
                                        id="lotacao_chefia" 
                                        value="{{ old('lotacao_chefia') }}"
                                        required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ex: João Silva - Coordenador"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Seção: Período do Contrato -->
                        <div class="mb-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Período do Contrato
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="contrato_data_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                        Data de Início *
                                    </label>
                                    <input 
                                        type="date" 
                                        name="contrato_data_inicio" 
                                        id="contrato_data_inicio" 
                                        value="{{ old('contrato_data_inicio') }}"
                                        required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                                <div>
                                    <label for="contrato_data_fim" class="block text-sm font-medium text-gray-700 mb-2">
                                        Data Final *
                                    </label>
                                    <input 
                                        type="date" 
                                        name="contrato_data_fim" 
                                        id="contrato_data_fim" 
                                        value="{{ old('contrato_data_fim') }}"
                                        required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Seção: Prorrogação (Opcional) -->
                        <div class="mb-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Prorrogação (Opcional)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="prorrogacao_data_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                        Início da Prorrogação
                                    </label>
                                    <input 
                                        type="date" 
                                        name="prorrogacao_data_inicio" 
                                        id="prorrogacao_data_inicio" 
                                        value="{{ old('prorrogacao_data_inicio') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                                <div>
                                    <label for="prorrogacao_data_fim" class="block text-sm font-medium text-gray-700 mb-2">
                                        Término da Prorrogação
                                    </label>
                                    <input 
                                        type="date" 
                                        name="prorrogacao_data_fim" 
                                        id="prorrogacao_data_fim" 
                                        value="{{ old('prorrogacao_data_fim') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Seção: Observações -->
                        <div class="mb-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Observações
                            </h4>
                            <div>
                                <label for="lotacao_observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observações Adicionais
                                </label>
                                <textarea 
                                    name="lotacao_observacoes" 
                                    id="lotacao_observacoes" 
                                    rows="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Informações adicionais sobre a convocação..."
                                >{{ old('lotacao_observacoes') }}</textarea>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.candidatos.ranking') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors border border-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Confirmar Convocação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-calcular data final baseada na data inicial (exemplo: 1 ano)
            const dataInicio = document.getElementById('contrato_data_inicio');
            const dataFim = document.getElementById('contrato_data_fim');
            
            dataInicio.addEventListener('change', function() {
                if (this.value && !dataFim.value) {
                    const inicio = new Date(this.value);
                    const fim = new Date(inicio);
                    fim.setFullYear(fim.getFullYear() + 1);
                    dataFim.value = fim.toISOString().split('T')[0];
                }
            });

            // Validação de datas
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const inicio = new Date(dataInicio.value);
                const fim = new Date(dataFim.value);
                
                if (fim <= inicio) {
                    e.preventDefault();
                    alert('A data final deve ser posterior à data inicial.');
                    dataFim.focus();
                }
            });
        });
    </script>
</x-app-layout>