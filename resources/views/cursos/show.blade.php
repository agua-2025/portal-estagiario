<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Curso: {{ $curso->nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background: linear-gradient(to right, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">
    <!-- Cabeçalho Simples (opcional, para manter a consistência) -->
    <header class="bg-white shadow-sm py-4 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ route('welcome') }}" class="text-2xl font-bold gradient-text">Portal do Estagiário</a>
            <nav>
                <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600 font-medium">Voltar ao Início</a>
            </nav>
        </div>
    </header>

    <main class="flex-1 container mx-auto px-4 py-12">
        {{-- ✅ BLOCO DE DEPURACAO: REMOVIDO --}}

        <div class="bg-white rounded-lg shadow-xl p-8 md:p-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">{{ $curso->nome }}</h1>
            
            {{-- Descrição Curta --}}
            @if ($curso->descricao)
                <p class="text-lg text-gray-700 mb-8 leading-relaxed">{{ $curso->descricao }}</p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                {{-- Valor da Bolsa-Auxílio --}}
                @if ($curso->valor_bolsa_auxilio && $curso->valor_bolsa_auxilio > 0)
                    <div class="bg-blue-50 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-blue-700 mb-2">Bolsa-Auxílio</h2>
                        <p class="text-2xl font-bold text-blue-900">R$ {{ number_format($curso->valor_bolsa_auxilio, 2, ',', '.') }}</p>
                    </div>
                @endif
                {{-- Valor do Auxílio Transporte --}}
                @if ($curso->valor_auxilio_transporte && $curso->valor_auxilio_transporte > 0)
                    <div class="bg-purple-50 p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold text-purple-700 mb-2">Auxílio Transporte</h2>
                        <p class="text-2xl font-bold text-purple-900">R$ {{ number_format($curso->valor_auxilio_transporte, 2, ',', '.') }}</p>
                    </div>
                @endif
            </div>

            {{-- Detalhes Completos do Curso --}}
            @if ($curso->detalhes)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Detalhes do Curso</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $curso->detalhes }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                {{-- Requisitos --}}
                @if ($curso->requisitos)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Requisitos</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $curso->requisitos }}</p>
                    </div>
                @endif
                {{-- Benefícios Adicionais --}}
                @if ($curso->beneficios)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Benefícios</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $curso->beneficios }}</p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                {{-- Carga Horária --}}
                @if ($curso->carga_horaria)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Carga Horária</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $curso->carga_horaria }}</p>
                    </div>
                @endif
                {{-- Local do Estágio --}}
                @if ($curso->local_estagio)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Local do Estágio</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $curso->local_estagio }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    &larr; Voltar para o Início
                </a>
            </div>
        </div>
    </main>
</body>
</html>
