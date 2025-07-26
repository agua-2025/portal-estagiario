<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nome -->
                        <div>
                            <x-input-label for="name" :value="__('Nome')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Campo de Senha (Opcional - apenas para alteração) -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Nova Senha (deixe em branco para não alterar)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirmação de Senha (Opcional - apenas para alteração) -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Nova Senha')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Atribuição de Papéis -->
                        <div class="mt-6">
                            <x-input-label :value="__('Papéis')" />
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($roles as $role)
                                    <label for="role_{{ $role->id }}" class="inline-flex items-center">
                                        <input id="role_{{ $role->id }}" type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                        />
                                        <span class="ms-2 text-sm text-gray-600">{{ ucfirst($role->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button class="ms-4" onclick="event.preventDefault(); window.location.href='{{ route('admin.users.index') }}'">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                            <x-primary-button class="ms-4">
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
