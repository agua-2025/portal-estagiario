<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Curso: {{ $curso->nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">
    <!-- Cabeçalho -->
    <header class="bg-white border-b border-gray-200 py-4 px-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('welcome') }}" class="text-xl font-semibold text-gray-900">Portal do Estagiário</a>
            <nav>
                <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">Voltar ao Início</a>
            </nav>
        </div>
    </header>

    <main class="flex-1 container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:p-8">
            <h1 class="text-xl md:text-2xl font-semibold text-gray-900 mb-3 leading-tight">{{ $curso->nome }}</h1>
            
            {{-- Descrição --}}
            @if ($curso->descricao)
                <p class="text-sm text-gray-700 mb-5 leading-relaxed">{{ $curso->descricao }}</p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Valor da Bolsa-Auxílio --}}
                @if ($curso->valor_bolsa_auxilio && $curso->valor_bolsa_auxilio > 0)
                    <div class="bg-blue-50 p-3 rounded border border-blue-100">
                        <h2 class="text-sm font-semibold text-blue-800 mb-1">Bolsa-Auxílio</h2>
                        <p class="text-base font-bold text-blue-900">R$ {{ number_format($curso->valor_bolsa_auxilio, 2, ',', '.') }}</p>
                    </div>
                @endif
                {{-- Valor do Auxílio Transporte --}}
                @if ($curso->valor_auxilio_transporte && $curso->valor_auxilio_transporte > 0)
                    <div class="bg-green-50 p-3 rounded border border-green-100">
                        <h2 class="text-sm font-semibold text-green-800 mb-1">Auxílio Transporte</h2>
                        <p class="text-base font-bold text-green-900">R$ {{ number_format($curso->valor_auxilio_transporte, 2, ',', '.') }}</p>
                    </div>
                @endif
            </div>

            {{-- Detalhes Completos do Curso --}}
            @if ($curso->detalhes)
                <div class="mb-5">
                    <h2 class="text-sm font-semibold text-gray-800 mb-2">Detalhes do Curso</h2>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $curso->detalhes }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                {{-- Requisitos --}}
                @if ($curso->requisitos)
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800 mb-2">Requisitos</h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $curso->requisitos }}</p>
                    </div>
                @endif
                {{-- Benefícios Adicionais --}}
                @if ($curso->beneficios)
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800 mb-2">Benefícios</h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $curso->beneficios }}</p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Carga Horária --}}
                @if ($curso->carga_horaria)
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800 mb-2">Carga Horária</h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $curso->carga_horaria }}</p>
                    </div>
                @endif
                {{-- Local do Estágio --}}
                @if ($curso->local_estagio)
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800 mb-2">Local do Estágio</h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $curso->local_estagio }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                    &larr; Voltar para o Início
                </a>
            </div>
        </div>
    </main>
</body>
</html>