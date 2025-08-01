<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Interpor Recurso de Classificação
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    @php
                        $candidato = Auth::user()->candidato;
                        $recursoPendente = false;
                        $recursoMaisRecente = null;

                        if (!empty($candidato->recurso_historico)) {
                            $recursoMaisRecente = $candidato->recurso_historico[0];
                            if (empty($recursoMaisRecente['decisao_admin'])) {
                                $recursoPendente = true;
                            }
                        }
                    @endphp

                    {{-- CASO 1: Já existe um recurso pendente de análise --}}
                    @if($recursoPendente)
                        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="text-lg font-bold text-blue-800">Recurso Enviado</h3>
                            <p class="text-sm text-gray-700 mt-2">Seu recurso foi enviado com sucesso em {{ \Carbon\Carbon::parse($recursoMaisRecente['data_envio'])->format('d/m/Y \à\s H:i') }} e está aguardando análise pela Comissão Organizadora.</p>
                            <div class="mt-4 p-4 bg-white border rounded-md text-sm">
                                <p class="font-semibold">Seu argumento:</p>
                                <p class="mt-1 text-gray-600 whitespace-pre-wrap">{{ $recursoMaisRecente['argumento_candidato'] }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline">Voltar ao Painel</a>
                        </div>

                    {{-- CASO 2: Candidato pode interpor recurso --}}
                    @elseif($candidato && $candidato->pode_interpor_recurso)
                        
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Formulário de Recurso de Classificação
                            </h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Utilize o campo abaixo para descrever de forma clara e objetiva os motivos do seu recurso contra a classificação final. Fundamente a sua argumentação com base nos critérios do edital.
                            </p>
                        </div>

                        {{-- Formulário --}}
                        <div class="border-t border-gray-200 mt-6 pt-6">
                            <p class="text-sm text-gray-800 mb-4">
                                <span class="font-bold">Prazo Final para Recurso:</span> 
                                
                                {{-- ✅ LINHA CORRIGIDA: Agora usa a variável $prazoFinal que veio do Controller --}}
                                {{ $prazoFinal->format('d/m/Y \à\s H:i') }}
                            </p>

                            <form action="{{ route('candidato.recurso.store') }}" method="POST">
                                @csrf
                                <div>
                                    <label for="recurso_texto" class="block text-sm font-medium text-gray-700">Apresente seus argumentos (mínimo 50 caracteres)</label>
                                    <textarea name="recurso_texto" id="recurso_texto" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required minlength="50">{{ old('recurso_texto') }}</textarea>
                                    @error('recurso_texto')
                                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-6 flex items-center justify-end gap-4">
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-semibold">
                                        Enviar Recurso para Análise
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                    {{-- CASO 3: Prazo para recurso já encerrou ou condição não atendida --}}
                    @else
                        <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                            <h3 class="text-lg font-bold text-yellow-800">Acesso Indisponível</h3>
                            <p class="mt-2 text-sm text-gray-700">O período para interpor recurso de classificação não está disponível ou já encerrou.</p>
                             <a href="{{ route('dashboard') }}" class="mt-4 inline-block text-sm font-semibold text-yellow-600 hover:underline">Voltar ao Painel</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>