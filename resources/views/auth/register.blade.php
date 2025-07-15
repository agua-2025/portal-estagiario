<x-guest-layout>
    {{-- PASSO 1: Adiciona o x-data para controlar o estado da checkbox --}}
    <form method="POST" action="{{ route('register') }}" x-data="{ terms: false }">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- ✅ PASSO 2: Adiciona a Checkbox de Termos e Condições -->
        <div class="block mt-4">
            <label for="terms" class="inline-flex items-center">
                <input id="terms" type="checkbox" name="terms" x-model="terms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                <span class="ms-2 text-sm text-gray-600">Eu li e concordo com os <a href="#" class="underline hover:text-gray-900">Termos de Uso e Política de Privacidade</a>.</span>
            </label>
            {{-- Mostra erro se o utilizador tentar submeter sem marcar --}}
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            {{-- ✅ PASSO 3: O botão agora é desabilitado com base na variável 'terms' --}}
            <x-primary-button class="ms-4" x-bind:disabled="!terms" x-bind:class="{ 'opacity-50 cursor-not-allowed': !terms }">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>