<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center space-x-3">
      <div class="p-2 bg-blue-100 rounded-lg">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ $doc->exists ? 'Editar Documento' : 'Novo Documento' }}
        </h2>
        <p class="text-sm text-gray-600 mt-1">
          {{ $doc->exists ? 'Atualize as informações do documento público' : 'Adicione um novo documento público ao sistema' }}
        </p>
      </div>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
          <div class="flex items-start">
            <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
              <h3 class="text-sm font-medium text-red-800">Corrija os seguintes erros:</h3>
              <ul class="mt-2 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                  <li class="flex items-center">
                    <span class="w-1 h-1 bg-red-400 rounded-full mr-2"></span>
                    {{ $error }}
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Informações do Documento</h3>
          <p class="text-sm text-gray-600 mt-1">Preencha os dados do documento público</p>
        </div>

        <form method="POST" enctype="multipart/form-data"
              action="{{ $doc->exists ? route('admin.public-docs.update', $doc) : route('admin.public-docs.store') }}"
              class="p-6">
          @csrf
          @if($doc->exists) @method('PUT') @endif

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="lg:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                  </svg>
                  Título do Documento
                </span>
              </label>
              <input name="title" 
                     value="{{ old('title', $doc->title) }}"
                     placeholder="Ex: Edital de Seleção 2024..."
                     class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                     required>
            </div>

            <!-- Tipo -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                  </svg>
                  Categoria
                </span>
              </label>
              <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                <option value="">Selecione uma categoria</option>
                <option value="edital" @selected(old('type',$doc->type)==='edital')>📋 Edital</option>
                <option value="manual" @selected(old('type',$doc->type)==='manual')>📖 Manual</option>
                <option value="cronograma" @selected(old('type',$doc->type)==='cronograma')>📅 Cronograma</option>
                <option value="lei" @selected(old('type',$doc->type)==='lei')>⚖️ Lei</option>
                <option value="decreto" @selected(old('type',$doc->type)==='decreto')>📜 Decreto</option>
                <option value="noticias" @selected(old('type',$doc->type)==='noticias')>📰 Notícias</option>
                <option value="convocacoes" @selected(old('type',$doc->type)==='convocacoes')>📢 Convocações</option>
              </select>
            </div>

            <!-- Data de Publicação -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Data de Publicação
                </span>
              </label>
              <input type="datetime-local" 
                     name="published_at"
                     value="{{ old('published_at', optional($doc->published_at)->format('Y-m-d\TH:i')) }}"
                     class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                     required>
            </div>

            <!-- Arquivo -->
            <div class="lg:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486L20.5 13"/>
                  </svg>
                  Arquivo {{ $doc->exists ? '(enviar para substituir)' : '' }}
                </span>
              </label>
              
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                <input
                  type="file"
                  name="file"
                  id="file_input"
                  @if(!$doc->exists) required @endif
                  accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/zip"
                  class="hidden"
                  onchange="updateFileName(this)"
                >
                <label for="file_input" class="cursor-pointer">
                  <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Clique para selecionar um arquivo</span>
                    <span class="text-xs text-gray-500 mt-1">PDF, DOC, XLS, PPT, ZIP (máx. 10MB)</span>
                  </div>
                </label>
                <div id="file_name" class="mt-3 text-sm text-blue-600 hidden"></div>
              </div>

              @if($doc->exists && $doc->file_path)
                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                  <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm text-blue-700">
                      Arquivo atual: <strong>{{ $doc->ext }}</strong>
                      @if($doc->size_human) • {{ $doc->size_human }} @endif
                    </span>
                  </div>
                </div>
              @endif
            </div>

            <!-- Status de Publicação -->
            <div class="lg:col-span-2">
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                  <div>
                    <label for="is_published" class="text-sm font-medium text-gray-700 cursor-pointer">
                      Status de Publicação
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Defina se o documento estará visível publicamente</p>
                  </div>
                  <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_published" 
                           name="is_published" 
                           value="1"
                           @checked(old('is_published', $doc->is_published))
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_published" class="ml-2 text-sm text-gray-700 cursor-pointer">
                      Publicado
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Botões de Ação -->
          <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <a href="{{ route('admin.public-docs.index') }}" 
                 class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Cancelar
              </a>
              
              <button type="submit" 
                      class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $doc->exists ? 'Atualizar Documento' : 'Salvar Documento' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function updateFileName(input) {
      const fileNameDiv = document.getElementById('file_name');
      if (input.files && input.files[0]) {
        fileNameDiv.textContent = `Arquivo selecionado: ${input.files[0].name}`;
        fileNameDiv.classList.remove('hidden');
      } else {
        fileNameDiv.classList.add('hidden');
      }
    }
  </script>
</x-app-layout>