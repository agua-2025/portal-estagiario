<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Lógica para mostrar menus diferentes para Admin e Candidato --}}
                    @auth 
                        @if (auth()->user()->role === 'admin')
                            
                            <x-nav-link :href="route('admin.instituicoes.index')" :active="request()->routeIs('admin.instituicoes.*')">
                                {{ __('Instituições') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.cursos.index')" :active="request()->routeIs('admin.cursos.*')">
                                {{ __('Cursos') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.tipos-de-atividade.index')" :active="request()->routeIs('admin.tipos-de-atividade.*')">
                                {{ __('Pontuação') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.candidatos.index')" :active="request()->routeIs('admin.candidatos.*')">
                                {{ __('Candidatos') }}
                            </x-nav-link>

                        @else {{-- Usuário logado que NÃO é admin (presume-se que seja um candidato) --}}

                            <div class="hidden sm:flex sm:items-center sm:ms-3">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>Meu Currículo</div>

                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('candidato.profile.edit')">
                                            Preencher/Editar Dados
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('candidato.documentos.index')">Enviar Documentos</x-dropdown-link>
                                        <x-dropdown-link :href="route('candidato.atividades.index')">Anexar Atividades</x-dropdown-link>
                                        
                                        {{-- ✅ INÍCIO DO AJUSTE CIRÚRGICO --}}
                                        @php
                                            $candidato = Auth::user()->candidato;
                                            $showRecursoLink = false;
                                            if ($candidato) {
                                                $isRejeitado = $candidato->status === 'Rejeitado';
                                                $isHomologadoEmPrazo = $candidato->status === 'Homologado' && $candidato->recurso_tipo === 'classificacao' && $candidato->recurso_prazo_ate && now()->lt($candidato->recurso_prazo_ate);
                                                if ($isRejeitado || $isHomologadoEmPrazo) {
                                                    $showRecursoLink = true;
                                                }
                                            }
                                        @endphp

                                        @if($showRecursoLink)
                                            <div class="border-t border-gray-200"></div>
                                            <x-dropdown-link :href="route('candidato.recurso.create')" class="font-bold text-red-600">
                                                {{ __('Interpor Recurso') }}
                                            </x-dropdown-link>
                                        @endif
                                        {{-- ✅ FIM DO AJUSTE --}}
                                    </x-slot>
                                </x-dropdown>
                            </div>
                            
                        @endif
                    @endauth
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    @auth 
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <svg class="h-6 w-6 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <div class="text-left">
                                        <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>

                                        @if(auth()->user()->role === 'admin')
                                            <div class="font-medium text-xs text-gray-500">Administrador</div>
                                        @endif
                                    </div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-gray-300 transition ease-in-out duration-150">Entrar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-gray-300 transition ease-in-out duration-150 ms-3">Inscreva-se</a>
                        @endif
                    @endauth
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if (auth()->user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.instituicoes.index')" :active="request()->routeIs('admin.instituicoes.*')">
                        {{ __('Instituições') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.cursos.index')" :active="request()->routeIs('admin.cursos.*')">
                        {{ __('Cursos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tipos-de-atividade.index')" :active="request()->routeIs('admin.tipos-de-atividade.*')">
                        {{ __('Pontuação') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.candidatos.index')" :active="request()->routeIs('admin.candidatos.*')">
                        {{ __('Candidatos') }}
                    </x-responsive-nav-link>
                @else {{-- Usuário logado que NÃO é admin (candidato) --}}
                    {{-- ✅ INÍCIO DO AJUSTE RESPONSIVO --}}
                    <div class="border-t border-gray-200 pt-2">
                        <x-responsive-nav-link :href="route('candidato.profile.edit')">
                            Preencher/Editar Dados
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('candidato.documentos.index')">
                            Enviar Documentos
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('candidato.atividades.index')">
                            Anexar Atividades
                        </x-responsive-nav-link>
                        
                        {{-- A mesma lógica do menu desktop é aplicada aqui --}}
                        @if(isset($showRecursoLink) && $showRecursoLink)
                            <x-responsive-nav-link :href="route('candidato.recurso.create')" class="font-bold text-red-600">
                                {{ __('Interpor Recurso') }}
                            </x-responsive-nav-link>
                        @endif
                    </div>
                    {{-- ✅ FIM DO AJUSTE RESPONSIVO --}}
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Entrar') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Inscreva-se') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
