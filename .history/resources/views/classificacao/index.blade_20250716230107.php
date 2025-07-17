<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificação Geral de Candidatos - Portal do Estagiário</title>
    {{-- Adicionando o Vite para carregar os estilos e scripts do seu projeto --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Adicionando o Alpine.js para a interatividade --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Estilos para harmonia visual com a Home Page */
        :root {
            --primary-color: #7C3AED; /* Roxo principal (botões, detalhes) */
            --dark-text: #1f2937;
            --light-text: #6b7280;
            --border-color: #e5e7eb;
            --background-light: #f9fafb;
            --status-approved-bg: #dcfce7;
            --status-approved-text: #166534;
            --status-pending-bg: #fef9c3;
            --status-pending-text: #854d0e;
            --status-rejected-bg: #fee2e2;
            --status-rejected-text: #991b1b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-light);
            color: var(--dark-text);
            margin: 0;
        }

        /* --- NOVO: Estilos do Menu de Navegação --- */
        .site-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            width: 100%;
            position: sticky; /* Opcional: para o menu ficar fixo no topo */
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-text);
            text-decoration: none;
        }

        .logo svg {
             color: var(--primary-color);
        }

        .navigation-links {
            display: flex;
            gap: 2rem;
        }

        .navigation-links a {
            color: var(--light-text);
            text-decoration: none;
            font-weight: 500;
            padding-bottom: 4px;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .navigation-links a:hover,
        .navigation-links a.active {
            color: var(--primary-color);
            font-weight: 600;
            border-bottom-color: var(--primary-color);
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: inline-block;
            text-align: center;
            border: 2px solid transparent;
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: #fff;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
        .btn-outline {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        /* --- Fim dos estilos do menu --- */

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .eyebrow-title {
            color: #3b82f6;
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-text);
            margin: 0.5rem 0;
        }

        .page-header p {
            color: var(--light-text);
            font-size: 1.1rem;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .card-header-title {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-header-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-text);
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
        }

        .results-table th,
        .results-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        
        .results-table th {
            color: var(--light-text);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            background-color: var(--background-light);
        }

        .results-table tr:last-of-type td {
            border-bottom: none;
        }

        .results-table tr.main-row:hover {
            background-color: #f3e8ff;
        }
        
        .position-cell {
            text-align: center;
            width: 5%;
        }
        .position-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.85rem;
        }
        .pos-1 { background-color: #f59e0b; color: #fff; }
        .pos-2 { background-color: #a3a3a3; color: #fff; }
        .pos-3 { background-color: #a16207; color: #fff; }
        .pos-other { background-color: #d4d4d8; color: var(--dark-text); }

        .status-badge {
            display: inline-block;
            padding: 0.3em 0.8em;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 9999px;
        }
        .status-approved {
            color: var(--status-approved-text);
            background-color: var(--status-approved-bg);
        }
        .status-pending {
            color: var(--status-pending-text);
            background-color: var(--status-pending-bg);
        }
        .status-rejected {
            color: var(--status-rejected-text);
            background-color: var(--status-rejected-bg);
        }
        
        .score {
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary-color);
        }

        .details-button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: 600;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }
        .details-button:hover {
            background-color: #f3e8ff;
        }

        .details-row td {
            background-color: var(--background-light);
            padding: 1rem 2rem;
        }
        .details-list dt { color: var(--light-text); }
        .details-list dd { font-weight: 500; }

        .no-results {
            text-align: center;
            padding: 3rem;
            background: #ffffff;
            border-radius: 12px;
        }

        /* Adaptação para telas menores */
        @media (max-width: 1024px) {
            .navigation-links {
                display: none; /* Em um site real, aqui entraria um menu hamburguer */
            }
        }
         @media (max-width: 768px) {
            .action-buttons {
                display: none;
            }
            .page-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    {{-- NOVO: Menu de Navegação do Site --}}
    <header class="site-header">
        <div class="header-container">
            <a href="/" class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                <span>Portal do Estagiário</span>
            </a>
            <nav class="navigation-links">
                <a href="#">Áreas de Estágio Disponíveis</a>
                <a href="#">Editais e Documentos</a>
                <a href="#" class="active">Classificação</a>
                <a href="#">Sobre</a>
            </nav>
            <div class="action-buttons">
                <a href="#" class="btn btn-outline">Entrar</a>
                <a href="#" class="btn btn-primary">Inscreva-se</a>
            </div>
            {{-- Adicionar um botão de menu hamburguer para mobile aqui --}}
        </div>
    </header>

    <main>
        <div class="container">
            <div class="page-header">
                <span class="eyebrow-title">TRANSPARÊNCIA</span>
                <h1>Classificação dos Candidatos</h1>
                <p>Acompanhe sua posição e pontuação em tempo real.</p>
            </div>

            @if($classificacaoPorCurso->isEmpty())
                <div class="no-results">
                    <h3>Nenhum resultado disponível</h3>
                    <p>A lista de classificação ainda não foi divulgada.</p>
                </div>
            @else
                @foreach($classificacaoPorCurso as $cursoNome => $candidatos)
                    <div class="card">
                        <div class="card-header-title">
                            <h2>{{ $cursoNome }}</h2>
                        </div>
                        
                        <div class="table-container">
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th class="position-cell">Pos.</th>
                                        <th>Candidato</th>
                                        <th>Instituição</th>
                                        <th>Status</th>
                                        <th>Pontuação</th>
                                        <th class="relative px-4 py-3"><span class="sr-only">Ações</span></th>
                                    </tr>
                                </thead>
                                @foreach($candidatos as $index => $candidato)
                                    <tbody x-data="{ open: false }">
                                        <tr class="main-row">
                                            <td class="position-cell">
                                                @if($index + 1 == 1)
                                                    <span class="position-circle pos-1">{{ $index + 1 }}</span>
                                                @elseif($index + 1 == 2)
                                                    <span class="position-circle pos-2">{{ $index + 1 }}</span>
                                                @elseif($index + 1 == 3)
                                                    <span class="position-circle pos-3">{{ $index + 1 }}</span>
                                                @else
                                                    <span class="position-circle pos-other">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $candidato->nome }}</td>
                                            <td>Não Informada</td> 
                                            <td>
                                                @if($candidato->status === 'Aprovado')
                                                    <span class="status-badge status-approved">Aprovado</span>
                                                @elseif($candidato->status === 'Inscrição Incompleta')
                                                    <span class="status-badge status-pending">Inscrição Incompleta</span>
                                                @else
                                                    <span class="status-badge status-rejected">Rejeitado</span>
                                                @endif
                                            </td>
                                            <td class="score">{{ number_format($candidato->pontuacao_final, 2, ',', '.') }}</td>
                                            <td class="text-right">
                                                <button @click="open = !open" class="details-button">
                                                    <span x-show="!open">Detalhes</span>
                                                    <span x-show="open">Esconder</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr x-show="open" x-transition class="details-row">
                                            <td colspan="6">
                                                <dl class="details-list">
                                                    <h4 class="font-semibold mb-2 text-xs uppercase tracking-wider">Extrato de Pontos:</h4>
                                                    @forelse($candidato->pontuacao_detalhes ?? [] as $detalhe)
                                                        <div class="flex justify-between py-1 border-b border-gray-200 last:border-0">
                                                            <dt>{{ $detalhe['nome'] }}</dt>
                                                            <dd>{{ number_format($detalhe['pontos'], 2, ',', '.') }}</dd>
                                                        </div>
                                                    @empty
                                                        <p class="italic text-gray-500">Nenhuma pontuação registrada.</p>
                                                    @endforelse
                                                </dl>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </main>

</body>
</html>