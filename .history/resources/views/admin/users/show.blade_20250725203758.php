<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">{{ __('Nome:') }} <span class="font-semibold text-gray-800">{{ $user->name }}</span></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">{{ __('Email:') }} <span class="font-semibold text-gray-800">{{ $user->email }}</span></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">{{ __('Status do Email:') }} 
                            <span class="font-semibold text-gray-800">
                                @if($user->hasVerifiedEmail())
                                    <span class="text-green-600">{{ __('Verificado') }}</span>
                                @else
                                    <span class="text-red-600">{{ __('Não Verificado') }}</span>
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">{{ __('Papéis:') }} 
                            <span class="font-semibold text-gray-800">
                                @forelse($user->getRoleNames() as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ ucfirst($role) }}
                                    </span>
                                @empty
                                    {{ __('Nenhum papel atribuído.') }}
                                @endforelse
                            </span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">{{ __('Data de Criação:') }} <span class="font-semibold text-gray-800">{{ $user->created_at->format('d/m/Y H:i') }}</span></p>
                    </div>
                    <div class="mb-6">
                        <p class="text-sm text-gray-600">{{ __('Última Atualização:') }} <span class="font-semibold text-gray-800">{{ $user->updated_at->format('d/m/Y H:i') }}</span></p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <x-primary-button onclick="event.preventDefault(); window.location.href='{{ route('admin.users.edit', $user) }}'">
                            {{ __('Editar Usuário') }}
                        </x-primary-button>
                        <x-secondary-button onclick="event.preventDefault(); window.location.href='{{ route('admin.users.index') }}'">
                            {{ __('Voltar para a Lista') }}
                        </x-secondary-button>
                        <!-- Botão de exclusão -->
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este usuário?') }}');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit">
                                {{ __('Excluir Usuário') }}
                            </x-danger-button>
                        </form>

                        <!-- Botão de Reenviar E-mail de Verificação -->
                        @if(!$user->hasVerifiedEmail())
                            <form method="POST" action="{{ route('admin.users.resend-verification', $user) }}">
                                @csrf
                                <x-secondary-button type="submit">
                                    {{ __('Reenviar E-mail de Verificação') }}
                                </x-secondary-button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>