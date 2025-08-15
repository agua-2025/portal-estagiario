<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ $doc->exists ? 'Editar Documento' : 'Novo Documento' }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-3">
          <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" enctype="multipart/form-data"
            action="{{ $doc->exists ? route('admin.public-docs.update', $doc) : route('admin.public-docs.store') }}"
            class="space-y-6 bg-white p-6 rounded-lg shadow">
        @csrf
        @if($doc->exists) @method('PUT') @endif

        <div>
          <label class="block text-sm font-medium text-gray-700">Título</label>
          <input name="title" value="{{ old('title', $doc->title) }}"
                 class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring"
                 required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Tipo</label>
          <select name="type" class="mt-1 w-full border rounded-lg px-3 py-2">
            <option value="">—</option>
            <option value="edital" @selected(old('type',$doc->type)==='edital')>Edital</option>
            <option value="manual" @selected(old('type',$doc->type)==='manual')>Manual</option>
            <option value="cronograma" @selected(old('type',$doc->type)==='cronograma')>Cronograma</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">
            Arquivo {{ $doc->exists ? '(enviar para substituir)' : '' }}
          </label>
          <input type="file" name="file" class="mt-1">
          @if($doc->exists && $doc->file_path)
            <p class="text-sm text-gray-600 mt-1">
              Atual: {{ $doc->ext }} @if($doc->size_human) • {{ $doc->size_human }} @endif
            </p>
          @endif
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Publicado em</label>
          <input type="datetime-local" name="published_at"
                 value="{{ old('published_at', optional($doc->published_at)->format('Y-m-d\TH:i')) }}"
                 class="mt-1 w-full border rounded-lg px-3 py-2" required>
        </div>

        <div class="flex items-center gap-2">
          <input type="checkbox" id="is_published" name="is_published" value="1"
                 @checked(old('is_published', $doc->is_published))>
          <label for="is_published" class="text-sm text-gray-700">Publicado</label>
        </div>

        <div class="flex items-center gap-3">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Salvar</button>
          <a href="{{ route('admin.public-docs.index') }}" class="px-4 py-2 border rounded-lg">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
