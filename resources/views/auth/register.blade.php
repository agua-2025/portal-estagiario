<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <!-- Header da tela de registro -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900">Criar conta</h2>
            <p class="mt-2 text-sm text-gray-600">Preencha os dados para se cadastrar</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="{ terms: false }">
            @csrf

            <!-- Nome -->
            <div>
                <x-input-label for="name" value="Nome completo" class="text-sm font-medium text-gray-700" />
                <div class="mt-2">
                    <x-text-input 
                        id="name" 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Seu nome completo"
                    />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- E-mail -->
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
                        autocomplete="username"
                        placeholder="seuemail@exemplo.com"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Senha -->
            <div>
                <x-input-label for="password" value="Senha" class="text-sm font-medium text-gray-700" />
                <div class="mt-2">
                    <x-text-input 
                        id="password" 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password"
                        required 
                        autocomplete="new-password"
                        placeholder="Mínimo 8 caracteres"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmar Senha -->
            <div>
                <x-input-label for="password_confirmation" value="Confirmar senha" class="text-sm font-medium text-gray-700" />
                <div class="mt-2">
                    <x-text-input 
                        id="password_confirmation" 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Digite a senha novamente"
                    />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Termos e Condições -->
            <div>
                <label for="terms" class="flex items-start">
                    <input 
                        id="terms" 
                        type="checkbox" 
                        name="terms" 
                        x-model="terms" 
                        class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 flex-shrink-0"
                    />
                    <span class="ml-3 text-sm text-gray-600 leading-relaxed">
                        Eu li e concordo com os 
                        <a 
                            target="_blank" 
                            href="{{ route('termos-de-uso') }}" 
                            class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition duration-200"
                        >
                            Termos de Uso
                        </a> 
                        e 
                        <a 
                            target="_blank" 
                            href="{{ route('politica-privacidade') }}" 
                            class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition duration-200"
                        >
                            Política de Privacidade
                        </a>.
                    </span>
                </label>
                <x-input-error :messages="$errors->get('terms')" class="mt-2" />
            </div>

            <!-- Botão de Registro -->
            <div>
                <x-primary-button 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition duration-200"
                    x-bind:disabled="!terms" 
                    x-bind:class="{ 
                        'bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500': terms,
                        'bg-gray-400 cursor-not-allowed': !terms 
                    }"
                >
                    {{ __('Criar conta') }}
                </x-primary-button>
            </div>
        </form>
        
        <!-- Link para login -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Já tem uma conta? 
                <a 
                    href="{{ route('login') }}" 
                    class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition duration-200"
                >
                    Faça login
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>