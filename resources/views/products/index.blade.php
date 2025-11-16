<!-- resources/views/products/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Produtos</h1>
    <div class="produtos-grid">
        @foreach ($products as $product)
            <div class="produto-card fade-in">
                <div class="produto-imagem">
                    <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name ?? $product->nome }}"
                        onerror="this.onerror=null;this.src='{{ asset('img/placeholder-product.svg') }}';" />
                </div>
                <div class="card-content">
                    <h3>{{ $product->name ?? $product->nome }}</h3>
                    <div class="descricao">{{ $product->description ?? $product->descricao }}</div>
                    <div class="preco">R$ {{ number_format($product->price ?? $product->preco, 2, ',', '.') }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endsection