<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('Cadastrar Nova Instituição') }}
                    </h2>

                    <form method="POST" action="{{ route('admin.instituicoes.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="nome" class="block font-medium text-sm text-gray-700">Nome da Instituição</label>
                                <input id="nome" name="nome" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required autofocus>
                            </div>

                            <div>
                                <label for="sigla" class="block font-medium text-sm text-gray-700">Sigla (Opcional)</label>
                                <input id="sigla" name="sigla" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label for="telefone_contato" class="block font-medium text-sm text-gray-700">Telefone de Contato</label>
                                <input id="telefone_contato" name="telefone_contato" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="endereco" class="block font-medium text-sm text-gray-700">Endereço</label>
                                <input id="endereco" name="endereco" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                             <div>
                                <label for="cidade" class="block font-medium text-sm text-gray-700">Cidade</label>
                                <input id="cidade" name="cidade" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                             <div>
                                <label for="estado" class="block font-medium text-sm text-gray-700">Estado</label>
                                <input id="estado" name="estado" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.instituicoes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Salvar Instituição
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>