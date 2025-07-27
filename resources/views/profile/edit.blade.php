<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Perfil do Usuário') }}
            </h2>
            <div class="text-sm text-gray-500">
                {{ __('Gerencie suas informações pessoais e configurações de conta') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Seção de Informações do Perfil -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ __('Informações Pessoais') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Atualize suas informações pessoais e endereço de email.') }}
                    </p>
                </div>
                <div class="p-6">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Seção de Segurança -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        {{ __('Segurança da Conta') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Mantenha sua conta segura atualizando sua senha regularmente.') }}
                    </p>
                </div>
                <div class="p-6">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Seção de Exclusão de Conta -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-red-200">
                <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-red-200">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ __('Zona de Perigo') }}
                    </h3>
                    <p class="mt-1 text-sm text-red-600">
                        {{ __('Esta ação é permanente e não pode ser desfeita. Todos os seus dados serão removidos.') }}
                    </p>
                </div>
                <div class="p-6">
                    <div class="max-w-2xl">
                        {{-- Exibição de erros da bag 'userDeletion' --}}
                        @if ($errors->userDeletion->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="font-medium text-red-800">
                                        {{ __('Erro ao processar solicitação') }}
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-red-700">
                                    {{ __('Ocorreu um erro ao tentar apagar sua conta. Verifique os detalhes abaixo:') }}
                                </div>
                                <ul class="mt-3 list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach ($errors->userDeletion->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé informativo -->
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-900 mb-1">{{ __('Dicas de Segurança') }}</p>
                        <p>{{ __('Mantenha suas informações sempre atualizadas e use senhas fortes. Em caso de dúvidas, entre em contato com o suporte.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>