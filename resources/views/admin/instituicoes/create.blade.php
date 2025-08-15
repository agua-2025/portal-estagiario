<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastrar Nova Instituição') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Corrija os seguintes erros:</h3>
                            <ul class="mt-2 text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-center">
                                        <span class="w-1 h-1 bg-red-400 rounded-full mr-2"></span>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações da Instituição</h3>
                    <p class="text-sm text-gray-600 mt-1">Preencha os dados da instituição de ensino</p>
                </div>

                <form method="POST" action="{{ route('admin.instituicoes.store') }}" class="p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome da Instituição</label>
                            <input id="nome" 
                                   name="nome" 
                                   type="text" 
                                   value="{{ old('nome') }}"
                                   placeholder="Ex: Universidade Federal do Estado"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   required 
                                   autofocus>
                        </div>

                        <div>
                            <label for="sigla" class="block text-sm font-medium text-gray-700 mb-2">Sigla (Opcional)</label>
                            <input id="sigla" 
                                   name="sigla" 
                                   type="text" 
                                   value="{{ old('sigla') }}"
                                   placeholder="Ex: UFES"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   maxlength="10">
                        </div>

                        <div>
                            <label for="telefone_contato" class="block text-sm font-medium text-gray-700 mb-2">Telefone de Contato</label>
                            <input id="telefone_contato" 
                                   name="telefone_contato" 
                                   type="tel" 
                                   value="{{ old('telefone_contato') }}"
                                   placeholder="(27) 99999-9999"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                            <input id="endereco" 
                                   name="endereco" 
                                   type="text" 
                                   value="{{ old('endereco') }}"
                                   placeholder="Rua, Número, Bairro, CEP"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   required>
                        </div>

                        <div>
                            <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                            <input id="cidade" 
                                   name="cidade" 
                                   type="text" 
                                   value="{{ old('cidade') }}"
                                   placeholder="Ex: Vitória"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   required>
                        </div>

                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select id="estado" 
                                    name="estado" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    required>
                                <option value="">Selecione um estado</option>
                                <option value="AC" {{ old('estado') == 'AC' ? 'selected' : '' }}>Acre</option>
                                <option value="AL" {{ old('estado') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                <option value="AP" {{ old('estado') == 'AP' ? 'selected' : '' }}>Amapá</option>
                                <option value="AM" {{ old('estado') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                <option value="BA" {{ old('estado') == 'BA' ? 'selected' : '' }}>Bahia</option>
                                <option value="CE" {{ old('estado') == 'CE' ? 'selected' : '' }}>Ceará</option>
                                <option value="DF" {{ old('estado') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                <option value="ES" {{ old('estado') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                <option value="GO" {{ old('estado') == 'GO' ? 'selected' : '' }}>Goiás</option>
                                <option value="MA" {{ old('estado') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                <option value="MT" {{ old('estado') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                <option value="MS" {{ old('estado') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                <option value="MG" {{ old('estado') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                <option value="PA" {{ old('estado') == 'PA' ? 'selected' : '' }}>Pará</option>
                                <option value="PB" {{ old('estado') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                <option value="PR" {{ old('estado') == 'PR' ? 'selected' : '' }}>Paraná</option>
                                <option value="PE" {{ old('estado') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                <option value="PI" {{ old('estado') == 'PI' ? 'selected' : '' }}>Piauí</option>
                                <option value="RJ" {{ old('estado') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                <option value="RN" {{ old('estado') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                <option value="RS" {{ old('estado') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                <option value="RO" {{ old('estado') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                <option value="RR" {{ old('estado') == 'RR' ? 'selected' : '' }}>Roraima</option>
                                <option value="SC" {{ old('estado') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                <option value="SP" {{ old('estado') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                <option value="SE" {{ old('estado') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                <option value="TO" {{ old('estado') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.instituicoes.index') }}" 
                           class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Salvar Instituição
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Máscara para telefone
        document.getElementById('telefone_contato').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 2) {
                    value = value.replace(/(\d{2})/, '($1) ');
                } else if (value.length <= 7) {
                    value = value.replace(/(\d{2})(\d{4,5})/, '($1) $2-');
                } else {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }
            }
            e.target.value = value;
        });

        // Converter sigla para maiúsculo
        document.getElementById('sigla').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</x-app-layout>