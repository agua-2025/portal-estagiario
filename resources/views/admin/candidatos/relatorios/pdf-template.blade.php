<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Candidatos</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            color: #333;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header .logo-container {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        .header .logo-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            margin-right: 10px;
        }
        .header .logo-icon svg {
            width: 100%;
            height: auto;
        }
        .header .header-info {
            text-align: right;
            flex-grow: 1;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            padding: 5px;
            background-color: #f2f2f2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
        }
        .footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 8px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .page-number:before {
            content: "Página " counter(page);
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-container">
            <div class="logo-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="logo-text">
                <h1>Portal do Estagiário</h1>
            </div>
        </div>
        <div class="header-info">
            <p>Endereço da Prefeitura, Cidade, UF</p>
            <p>Telefone: (00) 00000-0000 | CNPJ: XX.XXX.XXX/0001-XX</p>
        </div>
    </div>

    <div class="report-title">
        Relatório de Candidatos Inscritos
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Nome Completo</th>
                <th style="width: 15%;">CPF</th>
                <th style="width: 20%;">Curso</th>
                <th style="width: 20%;">Instituição</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Pontuação Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($candidatos as $candidato)
                <tr>
                    <td>{{ $candidato->nome_completo ?? 'N/A' }}</td>
                    <td>{{ $candidato->cpf ?? 'N/A' }}</td>
                    <td>{{ $candidato->curso->nome ?? 'N/A' }}</td>
                    <td>{{ $candidato->instituicao->nome ?? 'N/A' }}</td>
                    <td>{{ $candidato->status ?? 'N/A' }}</td>
                    <td>{{ number_format($candidato->pontuacao_final ?? 0, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Gerado em: {{ now()->format('d/m/Y H:i') }} | Página <span class="page-number"></span>
    </div>

</body>
</html>