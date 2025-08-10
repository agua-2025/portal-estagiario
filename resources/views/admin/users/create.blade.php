<x-app-layout>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Card Principal -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Header interno -->
                <div class="px-8 pt-8 pb-6 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ __('Novo Usuário') }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ __('Adicione um novo usuário ao sistema com as permissões adequadas') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-8" id="userForm">
                        @csrf

                        <!-- Dados Pessoais -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    {{ __('Nome Completo') }}
                                </label>
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    placeholder="Digite o nome completo"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                                />
                                @error('name')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    {{ __('E-mail') }}
                                </label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    placeholder="exemplo@email.com"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                                />
                                @error('email')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- CPF -->
                        <div class="space-y-2">
                            <label for="cpf" class="block text-sm font-medium text-gray-700">
                                {{ __('CPF') }}
                                <span class="text-xs text-orange-600 font-normal ml-1">({{ __('obrigatório para Candidato') }})</span>
                            </label>
                            <div class="max-w-md">
                                <input 
                                    id="cpf" 
                                    type="text" 
                                    name="cpf" 
                                    value="{{ old('cpf') }}" 
                                    placeholder="000.000.000-00"
                                    maxlength="14"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                                />
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ __('O CPF é obrigatório para usuários com papel de Candidato') }}
                            </p>
                            @error('cpf')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Senhas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Senha -->
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    {{ __('Senha') }}
                                </label>
                                <div class="relative">
                                    <input 
                                        id="password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="new-password" 
                                        placeholder="Digite uma senha segura"
                                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                                    />
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                                        <svg id="password-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmar Senha -->
                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    {{ __('Confirmar Senha') }}
                                </label>
                                <div class="relative">
                                    <input 
                                        id="password_confirmation" 
                                        type="password" 
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password" 
                                        placeholder="Confirme a senha"
                                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                                    />
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password_confirmation')">
                                        <svg id="password_confirmation-eye" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Papéis -->
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('Papéis') }}
                            </label>
                            <div class="space-y-3">
                                @foreach($roles as $role)
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                        <input 
                                            type="checkbox" 
                                            name="roles[]" 
                                            id="role_{{ $role }}" 
                                            value="{{ $role }}"
                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                                            {{ in_array($role, old('roles', [])) ? 'checked' : '' }}
                                        >
                                        <label for="role_{{ $role }}" class="text-sm font-medium text-gray-700 capitalize cursor-pointer">
                                            {{ $role }}
                                        </label>
                                        @if($role === 'admin')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Admin
                                            </span>
                                        @elseif($role === 'candidato')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Candidato
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('roles.*')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                {{ __('Cancelar') }}
                            </a>
                            
                            <button type="submit" class="px-6 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span>{{ __('Novo Usuário') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            
            if (value.length > 11) {
                value = value.slice(0, 11); // Limita a 11 dígitos
            }
            
            // Aplica a máscara
            if (value.length > 9) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d{3})/, '$1.$2');
            }
            
            e.target.value = value;
        });

        // Função para alternar visibilidade da senha
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                `;
            } else {
                field.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        // Validação em tempo real da confirmação de senha
        document.getElementById('password_confirmation').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = e.target.value;
            
            if (confirmation && password !== confirmation) {
                e.target.classList.add('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
                e.target.classList.remove('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            } else {
                e.target.classList.remove('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
                e.target.classList.add('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            </tr>
        });
    </script>
</x-app-layout>