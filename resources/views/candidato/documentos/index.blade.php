<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Meus Documentos</h2>
                        <p class="mt-1 text-sm text-gray-600">Envie os documentos necessários para validar a sua inscrição.</p>
                    </div>
                    {{-- 🔔 AVISO DE PERFIL INCOMPLETO --}}
                    @if(session('warn'))
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 rounded-r-lg" role="alert">
                        <p>{{ session('warn') }}</p>
                    </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-r-lg" role="alert">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($candidato->status === 'Inscrição Incompleta' && !empty($candidato->admin_observacao))
                        <div class="p-4 mb-6 border-l-4 border-red-500 bg-red-50 text-red-800 rounded-r-lg" role="alert">
                            <h3 class="font-bold">Correção Necessária!</h3>
                            <p class="mt-1 text-sm">A Comissão Organizadora solicitou uma correção no seu cadastro. O andamento do seu processo permanecerá suspenso até que o ajuste seja devidamente realizado.</p>
                        </div>
                    @endif

                    {{-- Lista de Documentos --}}
                    <div class="space-y-4">
                        @foreach ($documentosNecessarios as $tipo => $nome)
                            @php
                                $documentoEnviado = $documentosEnviados->get($tipo);
                            @endphp

                            <div class="py-4 border-b last:border-b-0">
                                <div class="flex flex-wrap justify-between items-start gap-4">
                                    <div class="flex-grow min-w-[200px] mb-2">
                                        <p class="font-semibold text-gray-800">{{ $nome }}</p>

                                        @if($documentoEnviado && $documentoEnviado->status === 'rejeitado' && !empty($documentoEnviado->motivo_rejeicao))
                                            <div class="mt-2 p-2 text-xs text-red-800 bg-red-50 rounded-md border border-red-200 break-words">
                                                <strong class="font-bold">Motivo da Rejeição:</strong> {{ $documentoEnviado->motivo_rejeicao }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center flex-shrink-0 gap-x-2">
                                        @if($documentoEnviado)
                                            <span class="font-semibold capitalize px-3 py-1.5 rounded-md text-xs
                                                @if($documentoEnviado->status == 'aprovado') bg-green-100 text-green-800 @endif
                                                @if($documentoEnviado->status == 'enviado' || $documentoEnviado->status == 'Em Análise') bg-purple-100 text-purple-800 @endif
                                                @if($documentoEnviado->status == 'rejeitado') bg-red-100 text-red-800 @endif
                                            ">{{ $documentoEnviado->status }}</span>
                                        @else
                                            <span class="font-semibold capitalize px-3 py-1.5 rounded-md text-xs bg-yellow-100 text-yellow-800">Pendente</span>
                                        @endif
                                        
                                        @if($documentoEnviado)
                                             <a href="{{ route('candidato.documentos.show', $documentoEnviado->id) }}" target="_blank" class="px-3 py-1.5 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold hover:bg-gray-300">Visualizar</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3">
                                    @if(!$documentoEnviado || $documentoEnviado->status === 'rejeitado')
                                        <form action="{{ route('candidato.documentos.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                                            @csrf
                                            <input type="hidden" name="tipo_documento" value="{{ $tipo }}">
                                            
                                            {{-- ✅ AJUSTE: Agrupando o input e a observação para melhor layout --}}
                                            <div class="flex-grow">
                                                @if($documentoEnviado?->status === 'rejeitado')
                                                    <span class="text-sm text-gray-600 mb-2 block">Substituir arquivo:</span>
                                                @endif
                                                <input type="file" name="documento" required accept="application/pdf, image/png, image/jpeg" class="text-sm text-slate-500 w-full file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                                {{-- ✅ NOVA OBSERVAÇÃO AQUI --}}
                                                <p class="mt-1 text-xs text-gray-500">Arquivos permitidos: PDF, JPG, PNG.</p>
                                            </div>
                                            
                                            <button type="submit" class="px-4 py-1.5 border border-blue-600 text-blue-700 rounded-md text-sm font-semibold hover:bg-blue-600 hover:text-white ml-auto transition-colors duration-200 flex-shrink-0">
                                                {{ $documentoEnviado?->status === 'rejeitado' ? 'Enviar Correção' : 'Enviar' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>