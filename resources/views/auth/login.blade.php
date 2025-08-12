<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input 
                id="email" 
                class="block w-full mt-1" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="seuemail@exemplo.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Senha" />
            <x-text-input 
                id="password" 
                class="block w-full mt-1"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="Sua senha"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="text-blue-600 border-gray-300 rounded shadow-sm focus:ring-blue-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">Lembrar de mim</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 underline rounded-md hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full text-center">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>
        
        <div class="mt-6 text-sm text-center text-gray-500">
            Não tem uma conta? 
            <a href="{{ route('register') }}" class="font-medium text-blue-600 underline hover:text-blue-800">
                Inscreva-se
            </a>
        </div>
    </form>
</x-guest-layout>