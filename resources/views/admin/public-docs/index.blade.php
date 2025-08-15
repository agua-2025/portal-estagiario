<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Documentos Públicos
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if(session('success'))
        <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3">
          {{ session('success') }}
        </div>
      @endif>

      <div class="flex items-center justify-between mb-6">
        <div></div>
        <a href="{{ route('admin.public-docs.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
          Novo
        </a>
      </div>

      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-left">
          <thead class="border-b">
            <tr class="text-sm text-gray-600">
              <th class="py-3 px-4">Título</th>
              <th class="py-3 px-4">Tipo</th>
              <th class="py-3 px-4">Publicado em</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4">Arquivo</th>
              <th class="py-3 px-4">Downloads</th>
              <th class="py-3 px-4">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @forelse($docs as $d)
              @php
                $typeMap = [
                  'edital' => 'bg-red-100 text-red-800',
                  'manual' => 'bg-blue-100 text-blue-800',
                  'cronograma' => 'bg-green-100 text-green-800',
                ];
                $badge = $typeMap[$d->type] ?? 'bg-gray-100 text-gray-800';
              @endphp
              <tr class="text-sm">
                <td class="py-3 px-4 font-medium text-gray-900">{{ $d->title }}</td>
                <td class="py-3 px-4">
                  @if($d->type)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs {{ $badge }}">
                      {{ ucfirst($d->type) }}
                    </span>
                  @else
                    —
                  @endif
                </td>
                <td class="py-3 px-4">{{ optional($d->published_at)->format('d/m/Y H:i') ?: '—' }}</td>
                <td class="py-3 px-4">
                  @if($d->is_published)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800 text-xs">Publicado</span>
                  @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 text-xs">Rascunho</span>
                  @endif
                </td>
                <td class="py-3 px-4">
                  {{ $d->ext }} @if($d->size_human) • {{ $d->size_human }} @endif
                </td>
                <td class="py-3 px-4">
                  {{ (int)($d->downloads ?? 0) }}
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-3">
                    <a class="text-blue-600 hover:underline" href="{{ route('admin.public-docs.edit',$d) }}">Editar</a>
                    <form method="POST" action="{{ route('admin.public-docs.destroy',$d) }}" onsubmit="return confirm('Excluir este documento?')">
                      @csrf @method('DELETE')
                      <button class="text-red-600 hover:underline" type="submit">Excluir</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-6 px-4 text-center text-gray-500">Nenhum documento cadastrado.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $docs->links() }}
      </div>
    </div>
  </div>
</x-app-layout>
