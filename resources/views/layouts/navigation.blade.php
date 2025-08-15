<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block w-auto text-gray-800 fill-current h-12" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ auth()->user()->role === 'admin' ? 'Painel Admin' : 'Meu Painel' }}
                    </x-nav-link>

                    @auth
                        @if (auth()->user()->role === 'admin')
                            {{-- MENU DE ADMIN (DESKTOP) --}}
                            <div class="relative flex" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                                    <span>Candidatos</span>
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-48 mt-16 rounded-md shadow-lg" style="display: none;">
                                    <div class="py-1 bg-white rounded-md ring-1 ring-black ring-opacity-5">
                                        <x-dropdown-link :href="route('admin.candidatos.index')">Listar Todos</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.candidatos.relatorios')">Relatórios</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.candidatos.ranking')">Convocação</x-dropdown-link>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="relative flex" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                                    <span>Configurações</span>
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-48 mt-16 rounded-md shadow-lg" style="display: none;">
                                    <div class="py-1 bg-white rounded-md ring-1 ring-black ring-opacity-5">
                                        <x-dropdown-link :href="route('admin.instituicoes.index')">Instituições</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cursos.index')">Cursos</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.tipos-de-atividade.index')">Regras de Pontuação</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.pages.index')">Páginas</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.public-docs.index')">{{ __('Documentos Públicos') }}</x-dropdown-link>
                                    </div>
                                </div>
                            </div>
                            
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                Gerenciar Usuários
                            </x-nav-link>
                        @else 
                            {{-- MENU DO CANDIDATO (DESKTOP) --}}
                            <div class="hidden sm:flex sm:items-center sm:ms-3">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>Meu Currículo</div>
                                            <div class="ms-1">
                                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('candidato.profile.edit')">Preencher/Editar Dados</x-dropdown-link>
                                        <x-dropdown-link :href="route('candidato.documentos.index')">Enviar Documentos</x-dropdown-link>
                                        <x-dropdown-link :href="route('candidato.atividades.index')">Anexar Atividades</x-dropdown-link>
                                        @if (Auth::user()->candidato?->pode_interpor_recurso)
                                            <div class="border-t border-gray-200"></div>
                                            <x-dropdown-link :href="route('candidato.recurso.create')" class="font-bold text-red-600">
                                                {{ __('Interpor Recurso') }}
                                            </x-dropdown-link>
                                        @endif
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- USER DROPDOWN (DESKTOP) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER BUTTON (MOBILE) --}}
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 border-t border-gray-200">
        @if(auth()->user()->role === 'candidato')
            {{-- MENU DO CANDIDATO (MOBILE) --}}
            <div class="py-4">
                {{-- Seção Principal --}}
                <div class="mb-4">
                    <div class="px-4 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Navegação</h3>
                    </div>
                    <div class="space-y-1 px-2">
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                            class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"/>
                            </svg>
                            Meu Painel
                        </x-responsive-nav-link>
                    </div>
                </div>

                {{-- Seção Currículo --}}
                <div class="mb-4">
                    <div class="px-4 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Meu Currículo</h3>
                    </div>
                    <div class="space-y-1 px-2">
                        <x-responsive-nav-link :href="route('candidato.profile.edit')" :active="request()->routeIs('candidato.profile.edit')"
                            class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Preencher/Editar Dados
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('candidato.documentos.index')" :active="request()->routeIs('candidato.documentos.index')"
                            class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Enviar Documentos
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('candidato.atividades.index')" :active="request()->routeIs('candidato.atividades.index')"
                            class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            Anexar Atividades
                        </x-responsive-nav-link>
                    </div>
                </div>

                {{-- Seção Recursos (se disponível) --}}
                @if (Auth::user()->candidato?->pode_interpor_recurso)
                    <div class="mb-4">
                        <div class="px-4 mb-2">
                            <h3 class="text-xs font-semibold text-red-500 uppercase tracking-wider">Recursos</h3>
                        </div>
                        <div class="space-y-1 px-2">
                            <x-responsive-nav-link :href="route('candidato.recurso.create')" 
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors bg-red-50 text-red-700 border border-red-200">
                                <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                {{ __('Interpor Recurso') }}
                            </x-responsive-nav-link>
                        </div>
                    </div>
                @endif
            </div>
        @else
            {{-- MENU DO ADMIN (MOBILE) --}}
            <div class="py-4">
                {{-- Seção Principal --}}
                <div class="mb-4">
                    <div class="px-4 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Administração</h3>
                    </div>
                    <div class="space-y-1 px-2">
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                            class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Painel Admin
                        </x-responsive-nav-link>
                    </div>
                </div>

                {{-- Seção Candidatos --}}
                <div class="mb-4">
                    <div x-data="{ openCand: false }">
                        <div class="px-4 mb-2">
                            <button @click="openCand = !openCand" class="w-full flex items-center justify-between">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Candidatos</h3>
                                <svg class="w-4 h-4 transform transition-transform text-gray-400" :class="{ 'rotate-180': openCand }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div x-show="openCand" x-transition class="space-y-1 px-2">
                            <x-responsive-nav-link :href="route('admin.candidatos.index')" :active="request()->routeIs('admin.candidatos.*')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Listar Todos
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.candidatos.relatorios')" :active="request()->routeIs('admin.candidatos.relatorios')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Relatórios
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.candidatos.ranking')" :active="request()->routeIs('admin.candidatos.ranking')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Convocação
                            </x-responsive-nav-link>
                        </div>
                    </div>
                </div>

                {{-- Seção Configurações --}}
                <div class="mb-4">
                    <div x-data="{ openCfg: false }">
                        <div class="px-4 mb-2">
                            <button @click="openCfg = !openCfg" class="w-full flex items-center justify-between">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Configurações</h3>
                                <svg class="w-4 h-4 transform transition-transform text-gray-400" :class="{ 'rotate-180': openCfg }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div x-show="openCfg" x-transition class="space-y-1 px-2">
                            <x-responsive-nav-link :href="route('admin.instituicoes.index')" :active="request()->routeIs('admin.instituicoes.*')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Instituições
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.cursos.index')" :active="request()->routeIs('admin.cursos.*')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Cursos
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.tipos-de-atividade.index')" :active="request()->routeIs('admin.tipos-de-atividade.*')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2zm8 0h-2a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2z"/>
                                </svg>
                                Regras de Pontuação
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.pages.index')" :active="request()->routeIs('admin.pages.*')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Páginas
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.public-docs.index')" :active="request()->routeIs('admin.public-docs.*')"
                                class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                </svg>
                                Documentos Públicos
                            </x-responsive-nav-link>
                        </div>
                    </div>
                </div>

                {{-- Seção Usuários --}}
                <div class="mb-4">
                    <div class="px-4 mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuários</h3>
                    </div>
                    <div class="space-y-1 px-2">
                        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                            class="flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197V9a3 3 0 00-6 0v2.25"/>
                            </svg>
                            Gerenciar Usuários
</nav>