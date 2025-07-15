<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Painel do Candidato
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-8">

                    {{-- Cabeçalho e Quadro de Avisos --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Bem-vindo, {{ $user->name }}!</h3>
                        <p class="text-gray-600 mt-2">Este é o seu Centro de Controle. Siga os passos abaixo para completar a sua inscrição.</p>
                    </div>

                    @if($itensRejeitadosCount > 0)
                        <div class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-800">
                                        <span class="font-semibold">Atenção!</span> Você tem 
                                        <strong class="font-bold text-yellow-900">{{ $itensRejeitadosCount }} {{ Str::plural('item', $itensRejeitadosCount) }}</strong> 
                                        que {{ $itensRejeitadosCount > 1 ? 'precisam' : 'precisa' }} da sua correção.
                                        <a href="{{ route('candidato.atividades.index') }}" class="inline-flex items-center font-semibold text-yellow-700 hover:text-yellow-600 underline decoration-2 underline-offset-2">
                                            Clique aqui para ver e corrigir
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Atalhos Rápidos --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('candidato.profile.edit') }}" class="group block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 hover:from-blue-100 hover:to-blue-200 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-full h-10 w-10 flex items-center justify-center text-white font-bold text-base shadow-md">1</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-blue-900 text-lg">Meu Currículo</h4>
                                    <p class="text-sm text-blue-700">Preencha seus dados</p>
                                </div>
                            </div>
                            @if($candidato)
                                <div class="w-full bg-blue-200 rounded-full h-2 mt-4">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $candidato->completion_percentage }}%"></div>
                                </div>
                                <p class="text-xs text-right text-blue-600 mt-2 font-medium">{{ $candidato->completion_percentage }}% completo</p>
                            @endif
                        </a>
                        
                        <a href="{{ route('candidato.documentos.index') }}" class="group block p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 hover:from-green-100 hover:to-green-200 hover:border-green-300 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-full h-10 w-10 flex items-center justify-center text-white font-bold text-base shadow-md">2</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-green-900 text-lg">Documentos</h4>
                                    <p class="text-sm text-green-700">Anexe os comprovantes</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-green-400 group-hover:text-green-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </a>
                        
                        <a href="{{ route('candidato.atividades.index') }}" class="group block p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 hover:from-purple-100 hover:to-purple-200 hover:border-purple-300 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-full h-10 w-10 flex items-center justify-center text-white font-bold text-base shadow-md">3</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-purple-900 text-lg">Saia na Frente</h4>
                                    <p class="text-sm text-purple-700">Adicione seus pontos</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-purple-400 group-hover:text-purple-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                        </a>
                    </div>

                    {{-- Seção de Classificação Refinada --}}
                    <div class="mt-8">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Sua Classificação</h3>
                            <p class="text-gray-600">Veja a sua posição na lista de aprovados para o seu curso.</p>
                        </div>

                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                            <div class="p-6">
                                @if($candidato && $candidato->curso)
                                    <div class="mb-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-800">{{ $candidato->curso->nome }}</h4>
                                        </div>
                                    </div>

                                    @if($classificacaoDoCurso->isEmpty())
                                        <div class="text-center py-12">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="mt-4 text-gray-500">A classificação para este curso ainda não foi divulgada ou não há candidatos aprovados.</p>
                                        </div>
                                    @else
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full">
                                                <thead>
                                                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Posição</th>
                                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Candidato</th>
                                                        @foreach($regrasDePontuacao as $regra)
                                                            <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $regra->nome }}</th>
                                                        @endforeach
                                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    @foreach($classificacaoDoCurso as $index => $classificado)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $classificado->user_id === Auth::id() ? 'bg-blue-50 border-l-4 border-blue-400' : '' }}">
                                                            <td class="px-3 py-3 text-center">
                                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-xs font-bold {{ $classificado->user_id === Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                                                    {{ $index + 1 }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <div class="text-sm font-medium {{ $classificado->user_id === Auth::id() ? 'text-blue-900' : 'text-gray-900' }}">
                                                                        {{ $classificado->user->name }}
                                                                        @if($classificado->user_id === Auth::id())
                                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                                Você
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @foreach($regrasDePontuacao as $regra)
                                                                <td class="px-3 py-3 text-center text-xs {{ $classificado->user_id === Auth::id() ? 'text-blue-900' : 'text-gray-700' }}">
                                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                                        {{ number_format($classificado->boletim_pontos[$regra->nome] ?? 0, 2, ',', '.') }}
                                                                    </span>
                                                                </td>
                                                            @endforeach
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $classificado->user_id === Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-800 text-white' }}">
                                                                    {{ number_format($classificado->pontuacao_final, 2, ',', '.') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        @if($classificacaoDoCurso->count() > 10)
                                            <div class="mt-4 text-center">
                                                <p class="text-xs text-gray-500">Mostrando {{ $classificacaoDoCurso->count() }} candidatos classificados</p>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <p class="mt-4 text-gray-500">Complete o seu perfil e selecione um curso para ver a classificação.</p>
                                        <a href="{{ route('candidato.profile.edit') }}" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                            Completar Perfil
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>