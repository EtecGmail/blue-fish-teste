<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas</title>
    <style>
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 12px;
            color: #0b2039;
        }
        h1 {
            text-align: center;
            color: #0066cc;
            margin-bottom: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #c5d9f5;
            padding: 8px;
            text-align: left;
        }
        thead {
            background: #e7f1ff;
        }
        tbody tr:nth-child(even) {
            background: #f5f9ff;
        }
    </style>
</head>
<body>
    <h1>Relatório de vendas</h1>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Cliente</th>
                <th>Quantidade</th>
                <th>Valor (R$)</th>
                <th>Status</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse($linhas as $linha)
                <tr>
                    @foreach($linha as $celula)
                        <td>{{ $celula }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhuma venda cadastrada até o momento.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
