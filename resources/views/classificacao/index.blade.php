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
        /* Estilos para uma aparência limpa, profissional e compacta */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a2e5a;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #6c757d;
            font-size: 1rem;
        }
        .info-note {
            text-align: center;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: -0.5rem;
            margin-bottom: 2.5rem;
        }
        .course-section {
            margin-bottom: 2.5rem;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .course-title {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        .course-title h2 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #1a2e5a;
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
            padding: 0.75rem 1rem; /* Padding ajustado para ser mais compacto */
            border-bottom: 1px solid #e9ecef;
            text-align: left;
            vertical-align: middle;
            font-size: 0.875rem; /* Fonte pequena */
            white-space: nowrap;
        }
        .results-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.7rem; /* Fonte do cabeçalho ainda menor */
            letter-spacing: 0.05em;
        }
        .results-table tr:last-of-type td {
            border-bottom: none;
        }
        .results-table tr.main-row:hover {
            background: #f1f3f5;
        }
        .position {
            text-align: center;
            font-weight: 700;
            width: 5%;
            color: #1a2e5a;
        }
        .name {
            font-weight: 500;
            white-space: normal; /* Permite quebra de linha no nome */
        }
        .cpf {
            color: #6c757d;
            font-size: 0.8rem;
        }
        .status-col {
            text-align: center;
        }
        .score {
            text-align: right;
            font-weight: 700;
            font-size: 1rem;
            color: #007bff;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        .status-approved {
            color: #155724;
            background-color: #d4edda;
        }
        .status-rejected {
            color: #721c24;
            background-color: #f8d7da;
        }
        .details-row td {
            background-color: #fafafa;
            padding: 1rem 1.5rem;
            white-space: normal;
        }
        .details-list {
            font-size: 0.85rem;
        }
        .details-list dt {
            color: #6c757d;
        }
        .details-list dd {
            font-weight: 500;
        }
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
            background: #ffffff;
            border-radius: 12px;
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
