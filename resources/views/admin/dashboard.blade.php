@extends('layouts.admin')

@section('title', 'Dashboard - Bluefish')

@section('content')
    <div class="admin-header">
        <h1>Painel Administrativo</h1>
        @auth
            <div class="admin-user" role="status">
                <div class="admin-user-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="admin-user-info">
                    <div class="admin-user-name">{{ auth()->user()->name }}</div>
                    <div class="admin-user-role">Administrador</div>
                </div>
            </div>
        @endauth
    </div>

    <section class="admin-stats" aria-label="Indicadores rápidos">
        <div class="stat-card" role="presentation">
            <div class="stat-card-header">
                <div class="stat-card-title">Usuários</div>
                <div class="stat-card-icon primary" aria-hidden="true"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['usuarios'] }}</div>
            <div class="stat-card-change positive" aria-live="polite">+0 hoje</div>
        </div>
        <div class="stat-card" role="presentation">
            <div class="stat-card-header">
                <div class="stat-card-title">Produtos</div>
                <div class="stat-card-icon success" aria-hidden="true"><i class="fas fa-fish"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['produtos'] }}</div>
            <div class="stat-card-change positive" aria-live="polite">+0 hoje</div>
        </div>
        <div class="stat-card" role="presentation">
            <div class="stat-card-header">
                <div class="stat-card-title">Contatos</div>
                <div class="stat-card-icon warning" aria-hidden="true"><i class="fas fa-envelope"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['contatos'] }}</div>
            <div class="stat-card-change" aria-live="polite">—</div>
        </div>
        <div class="stat-card" role="presentation">
            <div class="stat-card-header">
                <div class="stat-card-title">Vendas</div>
                <div class="stat-card-icon danger" aria-hidden="true"><i class="fas fa-receipt"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['vendas'] }}</div>
            <div class="stat-card-change positive" aria-live="polite">Faturamento R$ {{ number_format($stats['faturamento'], 2, ',', '.') }}</div>
        </div>
    </section>

    <section class="grid grid-2" style="margin-bottom: 2rem;" aria-label="Gráficos de desempenho">
        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Peixes mais vendidos (Google Charts)</h3>
                <div id="chart-pizza" data-chart="pizza" style="width: 100%; height: 320px;" role="img" aria-label="Gráfico de pizza com participação dos produtos"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Volume de vendas por produto (ECharts)</h3>
                <div id="chart-colunas" data-chart="barras" style="width: 100%; height: 320px;" role="img" aria-label="Gráfico de barras com volume de vendas"></div>
            </div>
        </div>
    </section>

    <section class="admin-table" aria-labelledby="titulo-ultimas-vendas">
        <div class="admin-table-header">
            <h2 class="admin-table-title" id="titulo-ultimas-vendas">Últimas vendas</h2>
            <div class="admin-table-actions" role="group" aria-label="Ações rápidas">
                <a href="{{ route('produtos.index') }}" class="btn btn-primary">Ver Produtos</a>
                <a href="{{ route('contato.form') }}" class="btn btn-secondary">Criar Aviso</a>
            </div>
        </div>
        <div class="export-actions" role="group" aria-label="Exportar relatório de vendas">
            <a href="{{ route('admin.sales.export', 'pdf') }}" class="btn btn-secondary">Exportar PDF</a>
            <a href="{{ route('admin.sales.export', 'csv') }}" class="btn btn-secondary">Exportar CSV</a>
            <a href="{{ route('admin.sales.export', 'xlsx') }}" class="btn btn-secondary">Exportar Excel</a>
        </div>
        <div class="admin-table-content">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Valor total</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentes as $venda)
                        <tr>
                            <td data-title="Produto">{{ optional($venda->produto)->nome ?? 'Produto removido' }}</td>
                            <td data-title="Cliente">{{ optional($venda->user)->name ?? 'Cliente removido' }}</td>
                            <td data-title="Quantidade">{{ $venda->quantidade }}</td>
                            <td data-title="Valor total">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                            <td data-title="Data">{{ optional($venda->created_at)->format('d/m/Y H:i') }}</td>
                            <td data-title="Ações">
                                @if($venda->produto)
                                    <a href="{{ route('produto.show', $venda->produto->id) }}" class="btn btn-primary">Ver produto</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Sem vendas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('scripts')
<script type="application/json" id="topProdutosData">{!! json_encode($topProdutos) !!}</script>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script>
    // Dados do controller para os gráficos
    const topProdutosEl = document.getElementById('topProdutosData');
    const topProdutos = topProdutosEl ? JSON.parse(topProdutosEl.textContent || '[]') : [];
    const temDados = Array.isArray(topProdutos) && topProdutos.some((p) => Number(p.quantidade) > 0);

    if (!temDados) {
        document.querySelectorAll('[data-chart]').forEach((chartEl) => {
            chartEl.innerHTML = '<p class="chart-empty">Sem dados suficientes para exibir o gráfico no período.</p>';
        });
    }

    // Google Charts - Pizza
    if (temDados) {
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(function() {
            const data = new google.visualization.DataTable();
            data.addColumn('string', 'Produto');
            data.addColumn('number', 'Quantidade');
            data.addRows(topProdutos.map(p => [p.nome, p.quantidade]));
            const options = {
                legend: { position: 'bottom' },
                chartArea: { width: '90%', height: '80%' }
            };
            const chart = new google.visualization.PieChart(document.getElementById('chart-pizza'));
            chart.draw(data, options);
        });
    }

    // ECharts - Colunas
    const el = document.getElementById('chart-colunas');
    if (el && temDados) {
        const chart = echarts.init(el);
        const option = {
            tooltip: {
                trigger: 'axis',
                formatter: function(params) {
                    if (!params.length) {
                        return '';
                    }
                    const item = params[0];
                    const produto = topProdutos[item.dataIndex];
                    const valor = produto ? produto.valor_total : 0;
                    return `${item.name}<br/>Quantidade: ${item.value}<br/>Faturamento: R$ ${Number(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                }
            },
            xAxis: {
                type: 'category',
                data: topProdutos.map(p => p.nome),
                axisLabel: { interval: 0 }
            },
            yAxis: { type: 'value' },
            series: [{
                type: 'bar',
                data: topProdutos.map(p => p.quantidade),
                itemStyle: { color: '#0066cc' }
            }],
            grid: { left: '3%', right: '4%', bottom: '10%', containLabel: true }
        };
        chart.setOption(option);
        window.addEventListener('resize', () => chart.resize());
    }
</script>
@endsection


