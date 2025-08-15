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
                            {{-- SEU MENU DE ADMIN CONTINUA AQUI, SEM ALTERAÇÕES --}}
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
                                        <x-dropdown-link :href="route('admin.public-docs.index')">{{ __('Documentos Públicos') }} </x-dropdown-link>
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        {{-- Lógica para mostrar o menu correto no mobile --}}
        @if(auth()->user()->role === 'candidato')
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Meu Painel
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('candidato.profile.edit')" :active="request()->routeIs('candidato.profile.edit')">
                    Preencher/Editar Dados
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('candidato.documentos.index')" :active="request()->routeIs('candidato.documentos.index')">
                    Enviar Documentos
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('candidato.atividades.index')" :active="request()->routeIs('candidato.atividades.index')">
                    Anexar Atividades
                </x-responsive-nav-link>

                @if (Auth::user()->candidato?->pode_interpor_recurso)
                    <div class="border-t border-gray-200"></div>
                    <x-responsive-nav-link :href="route('candidato.recurso.create')" class="font-bold text-red-600">
                        {{ __('Interpor Recurso') }}
                    </x-responsive-nav-link>
                @endif
            </div>
        @else
       <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    {{-- Lógica para mostrar o menu correto no mobile --}}
    @if(auth()->user()->role === 'candidato')
        {{-- MENU DO CANDIDATO (MOBILE) --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Meu Painel
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('candidato.profile.edit')" :active="request()->routeIs('candidato.profile.edit')">
                Preencher/Editar Dados
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('candidato.documentos.index')" :active="request()->routeIs('candidato.documentos.index')">
                Enviar Documentos
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('candidato.atividades.index')" :active="request()->routeIs('candidato.atividades.index')">
                Anexar Atividades
            </x-responsive-nav-link>

            @if (Auth::user()->candidato?->pode_interpor_recurso)
                <div class="border-t border-gray-200"></div>
                <x-responsive-nav-link :href="route('candidato.recurso.create')" class="font-bold text-red-600">
                    {{ __('Interpor Recurso') }}
                </x-responsive-nav-link>
            @endif
        </div>
    @else
        {{-- MENU DO ADMIN (MOBILE) --}}
        <div class="pt-2 pb-3 space-y-1">
            {{-- Painel Admin --}}
            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')">
                Painel Admin
            </x-responsive-nav-link>

            {{-- Candidatos (accordion) --}}
            <div x-data="{ openCand:false }" class="border-t border-gray-200 pt-2">
                <button @click="openCand = !openCand"
                        class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600">
                    <span>Candidatos</span>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': openCand }" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="openCand" x-transition class="mt-1 space-y-1 pl-6">
                    <x-responsive-nav-link :href="route('admin.candidatos.index')" :active="request()->routeIs('admin.candidatos.*')">
                        Listar Todos
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.candidatos.relatorios')" :active="request()->routeIs('admin.candidatos.relatorios')">
                        Relatórios
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.candidatos.ranking')" :active="request()->routeIs('admin.candidatos.ranking')">
                        Convocação
                    </x-responsive-nav-link>
                </div>
            </div>

            {{-- Configurações (accordion) --}}
            <div x-data="{ openCfg:false }" class="border-t border-gray-200 pt-2">
                <button @click="openCfg = !openCfg"
                        class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-600">
                    <span>Configurações</span>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': openCfg }" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="openCfg" x-transition class="mt-1 space-y-1 pl-6">
                    <x-responsive-nav-link :href="route('admin.instituicoes.index')" :active="request()->routeIs('admin.instituicoes.*')">
                        Instituições
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.cursos.index')" :active="request()->routeIs('admin.cursos.*')">
                        Cursos
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tipos-de-atividade.index')" :active="request()->routeIs('admin.tipos-de-atividade.*')">
                        Regras de Pontuação
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.pages.index')" :active="request()->routeIs('admin.pages.*')">
                        Páginas
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.public-docs.index')" :active="request()->routeIs('admin.public-docs.*')">
                        Documentos Públicos
                    </x-responsive-nav-link>
                </div>
            </div>

            {{-- Gerenciar Usuários --}}
            <div class="border-t border-gray-200 pt-2">
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    Gerenciar Usuários
                </x-responsive-nav-link>
            </div>
        </div>
    @endif

    {{-- Perfil + Logout --}}
    <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
        </div>
        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</div>
