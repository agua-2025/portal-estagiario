<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <!-- Header da tela de login -->
        <div class="mb-8 text-center">
            <p class="mt-2 text-sm text-gray-600">Faça login em sua conta</p>
        </div>

        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Campo E-mail -->
            <div>
                <x-input-label for="email" value="E-mail" class="text-sm font-medium text-gray-700" />
                <div class="mt-2">
                    <x-text-input 
                        id="email" 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="seuemail@exemplo.com"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Campo Senha -->
            <div>
                <x-input-label for="password" value="Senha" class="text-sm font-medium text-gray-700" />
                <div class="mt-2">
                    <x-text-input 
                        id="password" 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        placeholder="Sua senha"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Checkbox e Link de recuperação -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" 
                        name="remember"
                    >
                    <span class="ml-2 text-sm text-gray-600">Lembrar de mim</span>
                </label>

                @if (Route::has('password.request'))
                    <a 
                        class="text-sm font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition duration-200" 
                        href="{{ route('password.request') }}"
                    >
                        Esqueceu a senha?
                    </a>
                @endif
            </div>

            <!-- Botão de Login -->
            <div>
                <x-primary-button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    {{ __('Entrar') }}
                </x-primary-button>
            </div>
        </form>
        
        <!-- Link para registro -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Não tem uma conta? 
                <a 
                    href="{{ route('register') }}" 
                    class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition duration-200"
                >
                    Inscreva-se
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>