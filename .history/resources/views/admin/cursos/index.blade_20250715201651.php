@extends('layouts.admin') {{-- Estende o nosso layout admin --}}

@section('content')
{{-- ✅ REMOVIDO: div.container mx-auto externo. O layout pai (layouts.admin) fará a centralização. --}}
<div class="px-4 py-8"> {{-- Mantido apenas o padding --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestão de Cursos</h1>
        <a href="{{ route('admin.cursos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition duration-150 ease-in-out">
            Novo Curso
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sucesso!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nome do Curso
                        </th>
                        {{-- REMOVIDO: Coluna Instituição --}}
                        {{--
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Instituição
                        </th>
                        --}}
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($cursos as $curso)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $curso->nome }}
                            </td>
                            {{-- REMOVIDO: Exibição do nome da instituição --}}
                            {{--
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $curso->instituicao->nome ?? 'N/A' }}
                            </td>
                            --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                {{-- Estilo do botão Editar --}}
                                <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja apagar este curso?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Apagar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- CORRIGIDO: colspan para 2 colunas --}}
                            <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Nenhum curso cadastrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection