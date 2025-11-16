@extends('layouts.app')

@section('title', 'Minhas Compras - Bluefish')

@section('content')
    <div class="vendas-header fade-in">
        <h1>Minhas Compras</h1>
        <p>Acompanhe todas as compras realizadas na plataforma</p>
    </div>

    <div class="card vendas-card fade-in">
        @if($vendas->isEmpty())
            <p>Você ainda não realizou compras. Explore os <a href="{{ route('produtos.index') }}">produtos disponíveis</a> e faça seu primeiro pedido.</p>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendas as $venda)
                            <tr>
                                <td>{{ $venda->produto->nome ?? 'Produto indisponível' }}</td>
                                <td>{{ $venda->quantidade }}</td>
                                <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-success">{{ ucfirst($venda->status) }}</span>
                                </td>
                                <td>{{ optional($venda->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($venda->produto)
                                        <a href="{{ route('produto.show', $venda->produto) }}" class="btn btn-secondary btn-sm">Ver produto</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
