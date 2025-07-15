<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Seção de Informações do Perfil --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Informações do Perfil
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Atualize as informações de perfil e o endereço de e-mail da sua conta.
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nome</label>
                                <input id="name" name="name" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                                @error('name')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input id="email" name="email" type="email" class="mt-1 block w-full rounded-md shadow-sm border-gray-300" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                                 @error('email')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Salvar</button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- Seção de Atualizar Senha (pode adicionar depois) --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                   <p class="font-medium">Atualizar Senha (a fazer)</p>
                </div>
            </div>

            {{-- Seção de Apagar Conta (pode adicionar depois) --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <p class="font-medium">Apagar Conta (a fazer)</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>