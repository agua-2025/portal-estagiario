<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Portal do Estagiário</title>
    <!-- Tailwind CSS CDN (para simplificar, em um projeto real você usaria o build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Você pode adicionar seus próprios estilos CSS aqui -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Estilo para o dropdown */
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex flex-col h-screen">
        <!-- Topbar (Barra Superior do Admin) -->
        <header class="bg-white shadow py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <!-- Logo/Nome do Site -->
                <a href="{{ route('welcome') }}" class="text-2xl font-bold text-gray-800">Portal do Estagiário</a>

                <!-- Navegação Principal -->
                <nav class="hidden md:flex space-x-4 text-gray-600">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 font-medium">Painel</a>
                    <a href="{{ route('admin.instituicoes.index') }}" class="hover:text-blue-600 font-medium">Instituições</a>
                    <a href="{{ route('admin.cursos.index') }}" class="hover:text-blue-600 font-medium">Cursos</a>
                    <a href="#" class="hover:text-blue-600 font-medium">Pontuação</a> {{-- Ajuste a rota se houver --}}
                    <a href="{{ route('admin.candidatos.index') }}" class="hover:text-blue-600 font-medium">Candidatos</a>
                </nav>
            </div>

            <!-- Menu do Usuário Admin -->
            <div class="relative dropdown">
                <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                    <span class="font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a> {{-- Rota padrão do Breeze --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sair</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Sucesso!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erro!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="max-w-7xl mx-auto"> {{-- Container para centralizar o conteúdo --}}
                @yield('content') {{-- O conteúdo de cada página individual será injetado aqui --}}
            </div>
        </main>
    </div>
</body>
</html>