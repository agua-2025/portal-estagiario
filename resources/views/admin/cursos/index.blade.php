<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Gestão de Cursos') }}
                        </h2>
                        <a href="{{ route('admin.cursos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Novo Curso
                    </a>
                    </div>
                    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<table class="min-w-full ...">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome do Curso</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instituição</th>
                                <th class="relative px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($cursos as $curso)
                                <tr>
                                    <td class="px-6 py-4">{{ $curso->nome }}</td>
                                    <td class="px-6 py-4">{{ $curso->instituicao->nome ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right">
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</a>
                                    <form class="inline-block" method="POST" action="{{ route('admin.cursos.destroy', $curso->id) }}" onsubmit="return confirm('Tem certeza que deseja apagar este curso?');">
                                    @csrf
                            @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900">
            Apagar
        </button>
    </form>
</td>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Nenhum curso cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>