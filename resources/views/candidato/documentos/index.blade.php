<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Meus Documentos</h2>
                        <p class="mt-1 text-sm text-gray-600">Envie os documentos necessários para validar a sua inscrição.</p>
                    </div>

                    {{-- Verificação de Perfil Completo --}}
                    @if (!$candidato->isProfileComplete())
                        
                        {{-- MENSAGEM DE BLOQUEIO SE O PERFIL ESTIVER INCOMPLETO --}}
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                            <p class="font-bold">Ação Necessária</p>
                            <p>Por favor, complete 100% do seu perfil de dados cadastrais na página "Preencher/Editar Dados" antes de enviar os seus documentos.</p>
                            
                            {{-- ✅ NOVO BLOCO DE DEBUG: ADICIONE ISTO --}}
                            <div class="mt-3 p-2 border border-red-300 bg-red-50 text-red-900 text-xs rounded">
                                <strong>Informação de Debug:</strong>
                                <p>O sistema identificou que o campo "<strong>{{ $candidato->getFirstIncompleteField() ?? 'Não foi possível identificar' }}</strong>" está em falta.</p>
                                <p>Por favor, volte ao seu perfil e verifique se este campo está preenchido corretamente.</p>
                            </div>
                            {{-- FIM DO BLOCO DE DEBUG --}}

                            <a href="{{ route('candidato.profile.edit') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Completar Meu Perfil
                            </a>
                        </div>

                    @else

                        {{-- Mensagens de Sucesso e Erro --}}
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

                        {{-- A lista de documentos --}}
                        <div class="space-y-4">
                            @foreach ($documentosNecessarios as $tipo => $nome)
                                <div class="p-4 border rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">
                                    {{-- Seção de Informações do Documento --}}
                                    <div class="flex-grow text-center sm:text-left">
                                        <p class="font-semibold">{{ $nome }}</p>
                                        @php
                                            $documentoEnviado = $documentosEnviados->get($tipo);
                                        @endphp

                                        @if($documentoEnviado)
                                            <span class="text-xs font-medium capitalize px-2.5 py-0.5 rounded-full
                                                @if($documentoEnviado->status == 'aprovado') bg-green-100 text-green-800 @endif
                                                @if($documentoEnviado->status == 'enviado') bg-blue-100 text-blue-800 @endif
                                                @if($documentoEnviado->status == 'rejeitado') bg-red-100 text-red-800 @endif
                                            ">
                                                Status: {{ $documentoEnviado->status }}
                                            </span>
                                        @else
                                            <span class="text-xs font-medium capitalize px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                                Status: Pendente
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Seção de Ações --}}
                                    <div class="w-full sm:w-auto">
                                        @if($documentoEnviado)
                                            {{-- MOSTRA BOTÕES DE VISUALIZAR/SUBSTITUIR SE JÁ FOI ENVIADO --}}
                                            <div x-data="{ showUpload: false }" class="flex items-center justify-center sm:justify-end space-x-2 flex-wrap gap-2">
                                                <a href="{{ route('candidato.documentos.show', $documentoEnviado->id) }}" target="_blank" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700 whitespace-nowrap">
                                                    Visualizar
                                                </a>
                                                <button type="button" @click="showUpload = !showUpload" class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 whitespace-nowrap">Substituir</button>
                                                
                                                <div x-show="showUpload" x-transition class="mt-2 w-full basis-full">
                                                    <form action="{{ route('candidato.documentos.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                        @csrf
                                                        <input type="hidden" name="tipo_documento" value="{{ $tipo }}">
                                                        <input type="file" name="documento" class="text-sm text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required/>
                                                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Enviar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            {{-- MOSTRA FORMULÁRIO DE UPLOAD SE ESTIVER PENDENTE --}}
                                            <form action="{{ route('candidato.documentos.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center justify-center sm:justify-end space-x-2">
                                                @csrf
                                                <input type="hidden" name="tipo_documento" value="{{ $tipo }}">
                                                <input type="file" name="documento" class="text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required/>
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 whitespace-nowrap">
                                                    Enviar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>