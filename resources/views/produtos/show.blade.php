@extends('layouts.app')

@section('title', $produto->nome . ' - Bluefish')

@section('content')
    <article class="produto-detalhes">
        <div class="produto-imagem-detalhe">
            <img src="{{ $produto->imagem_url }}" alt="Imagem do produto {{ $produto->nome }}">
        </div>
        <div class="produto-info-detalhe">
            <header>
                <h1>{{ $produto->nome }}</h1>
                <p class="preco">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
            </header>
            <p>{{ $produto->descricao }}</p>
            @if(!is_null($produto->estoque))
                <p class="estoque">
                    <i class="fas {{ $produto->estoque > 0 ? 'fa-box' : 'fa-triangle-exclamation' }}" aria-hidden="true"></i>
                    @if($produto->estoque > 0)
                        <span>{{ $produto->estoque }} unidade{{ $produto->estoque === 1 ? '' : 's' }} disponíveis</span>
                    @else
                        <span>Indisponível no momento</span>
                    @endif
                </p>
            @endif

            @auth
                @if(!is_null($produto->estoque) && $produto->estoque < 1)
                    <p class="estoque" role="status">
                        <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
                        <span>Produto temporariamente indisponível para compra.</span>
                    </p>
                @else
                    <form action="{{ route('vendas.store') }}" method="POST" class="produto-compra-form" aria-label="Comprar {{ $produto->nome }}">
                        @csrf
                        <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                        <label class="quantidade-label" for="quantidade-produto">Quantidade</label>
                        <input
                            type="number"
                            id="quantidade-produto"
                            name="quantidade"
                            value="1"
                            min="1"
                            @if(!is_null($produto->estoque)) max="{{ $produto->estoque }}" @endif
                            class="quantidade-input"
                            required
                        >
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                            <span>Adicionar ao pedido</span>
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login.form') }}" class="btn btn-primary">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    <span>Entre para comprar</span>
                </a>
            @endauth
        </div>
    </article>
@endsection
