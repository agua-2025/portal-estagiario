<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificação Geral de Candidatos</title>
    {{-- Adicionando o Vite para carregar os estilos e scripts do seu projeto --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Adicionando o Alpine.js para a interatividade --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Estilos inspirados no Portal do Estagiário */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header p {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 500;
        }
        .info-note {
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: -0.5rem;
            margin-bottom: 2.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        .course-section {
            margin-bottom: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .course-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            border-bottom: none;
        }
        .course-title h2 {
            font-size: 1.6rem;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
            margin: 0;
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
            padding: 1rem;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            text-align: left;
            vertical-align: middle;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .results-table th {
            background: rgba(102, 126, 234, 0.05);
            font-weight: 600;
            color: #667eea;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }
        .results-table tr:last-of-type td {
            border-bottom: none;
        }
        .results-table tr.main-row:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        .position {
            text-align: center;
            font-weight: 700;
            width: 5%;
            color: #667eea;
            font-size: 1.1rem;
        }
        .name {
            font-weight: 500;
            white-space: normal;
            color: #2d3748;
        }
        .cpf {
            color: #6c757d;
            font-size: 0.85rem;
        }
        .status-col {
            text-align: center;
        }
        .score {
            text-align: right;
            font-weight: 700;
            font-size: 1.1rem;
            color: #667eea;
        }
        .status-badge {
            display: inline-block;
            padding: 0.4em 0.8em;
            font-size: 0.8rem;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-approved {
            color: #ffffff;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            box-shadow: 0 2px 8px rgba(72, 187, 120, 0.3);
        }
        .status-rejected {
            color: #ffffff;
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            box-shadow: 0 2px 8px rgba(245, 101, 101, 0.3);
        }
        .details-row td {
            background: rgba(102, 126, 234, 0.02);
            padding: 1.5rem;
            white-space: normal;
        }
        .details-list {
            font-size: 0.9rem;
        }
        .details-list dt {
            color: #4a5568;
            font-weight: 500;
        }
        .details-list dd {
            font-weight: 600;
            color: #667eea;
        }
        .details-list h4 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        .details-list .flex {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }
        .details-list .flex:last-child {
            border-bottom: none;
        }
        .no-results {
            text-align: center;
            padding: 4rem;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }
        .no-results h3 {
            color: #ffffff;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        /* Botão de detalhes */
        .text-indigo-600 {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(102, 126, 234, 0.1);
        }
        .text-indigo-600:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-1px);
        }
        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 0.5rem;
            }
            .header h1 {
                font-size: 2rem;
            }
            .results-table th,
            .results-table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Classificação Geral de Candidatos</h1>
            <p>Resultado do processo seletivo, organizado por curso e ordenado por pontuação.</p>
        </div>

        {{-- ✅ NOTAS IMPORTANTES PARA O UTILIZADOR --}}
        <div class="info-note">
            <p>Esta lista é dinâmica e pode ser atualizada conforme as análises são concluídas.</p>
            <p>Critério de desempate: maior idade.</p>
        </div>

        @if($classificacaoPorCurso->isEmpty())
            <div class="no-results">
                <h3>Nenhum resultado disponível</h3>
                <p>A lista de classificação ainda não foi divulgada.</p>
            </div>
        @else
            @foreach($classificacaoPorCurso as $cursoNome => $candidatos)
                <div class="course-section">
                    <div class="course-title">
                        <h2>{{ $cursoNome }}</h2>
                    </div>
                    
                    <div class="table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th class="position">Pos.</th>
                                    <th class="name">Nome do Candidato</th>
                                    <th class="cpf">CPF</th>
                                    <th class="status-col">Status</th>
                                    <th class="score">Pontuação</th>
                                    <th class="relative px-4 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            @foreach($candidatos as $index => $candidato)
                                {{-- ✅ ESTRUTURA DE TABELA EXPANSÍVEL --}}
                                <tbody x-data="{ open: false }">
                                    <tr class="main-row">
                                        <td class="position">{{ $index + 1 }}º</td>
                                        <td class="name">{{ $candidato->nome }}</td>
                                        <td class="cpf">{{ substr($candidato->cpf, 0, 3) }}.***.***-**</td>
                                        <td class="status-col">
                                            @if($candidato->status === 'Aprovado')
                                                <span class="status-badge status-approved">Aprovado</span>
                                            @else
                                                <span class="status-badge status-rejected">Rejeitado</span>
                                            @endif
                                        </td>
                                        <td class="score">{{ number_format($candidato->pontuacao_final, 2, ',', '.') }}</td>
                                        <td class="text-right">
                                            <button @click="open = !open" class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">
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
</body>
</html>