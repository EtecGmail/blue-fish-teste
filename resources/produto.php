@extends('layouts.app')

@section('content')
<div class="container">
    <div class="produto-detalhe fade-in">
        <div class="grid">
            <div class="produto-imagem">
                <img src="{{ asset($produto->imagem) }}" alt="{{ $produto->nome }}" class="card">
            </div>
            <div class="produto-info">
                <h1>{{ $produto->nome }}</h1>
                <p class="descricao">{{ $produto->descricao }}</p>
                <div class="preco-container">
                    <p class="preco">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                    <button class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                    </button>
                </div>
                <div class="produto-detalhes">
                    <h3>Detalhes do Produto</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Produto fresco e de alta qualidade</li>
                        <li><i class="fas fa-check"></i> Entrega em até 24 horas</li>
                        <li><i class="fas fa-check"></i> Garantia de satisfação</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection