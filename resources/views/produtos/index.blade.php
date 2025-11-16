@extends('layouts.app')

@section('title', 'Produtos - Bluefish')

@section('content')
    <div class="produtos-header fade-in">
        <h1>Nossos Produtos</h1>
        <p>Descubra nossa seleção de produtos frescos e de alta qualidade</p>
    </div>
    <div class="produtos-filtros fade-in" role="search" aria-label="Filtrar produtos">
        <div class="filtro-busca">
            <label class="sr-only" for="busca">Buscar produtos</label>
            <input type="text" id="busca" placeholder="Buscar produtos...">
            <i class="fas fa-search"></i>
        </div>
        <div class="filtro-ordem">
            <label class="sr-only" for="ordem">Ordenar produtos</label>
            <select id="ordem">
                <option value="nome">Nome</option>
                <option value="preco_asc">Menor Preço</option>
                <option value="preco_desc">Maior Preço</option>
            </select>
        </div>
    </div>
    <div class="grid produtos-grid" role="list">
        @forelse($produtos as $produto)
            <div class="card produto-card fade-in" role="listitem">
                <div class="produto-imagem">
                    <img src="{{ $produto->imagem_url }}" alt="Imagem do produto {{ $produto->nome }}" loading="lazy">
                    <div class="produto-overlay">
                        <a href="{{ route('produto.show', $produto->id) }}" class="btn btn-primary">
                            Ver detalhes
                        </a>
                    </div>
                </div>
                <div class="card-content">
                    <h3>{{ $produto->nome }}</h3>
                    <p class="descricao">{{ \Illuminate\Support\Str::limit($produto->descricao, 140) }}</p>
                    <p class="preco">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                    @if(!is_null($produto->estoque))
                        <p class="estoque" aria-live="polite">
                            <i class="fas fa-box" aria-hidden="true"></i>
                            <span>{{ $produto->estoque }} unidade{{ $produto->estoque === 1 ? '' : 's' }} em estoque</span>
                        </p>
                    @endif
                    @auth
                        @if(!is_null($produto->estoque) && $produto->estoque < 1)
                            <p class="estoque" role="status">
                                <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
                                <span>Indisponível no momento</span>
                            </p>
                        @else
                            <form action="{{ route('vendas.store') }}" method="POST" class="produto-compra-form">
                                @csrf
                                <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                                <label class="quantidade-label" for="quantidade-{{ $produto->id }}">Quantidade</label>
                                <input
                                    type="number"
                                    id="quantidade-{{ $produto->id }}"
                                    name="quantidade"
                                    value="1"
                                    min="1"
                                    @if(!is_null($produto->estoque)) max="{{ $produto->estoque }}" @endif
                                    class="quantidade-input"
                                    required
                                >
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-shopping-cart"></i> Comprar agora
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login.form') }}" class="btn btn-secondary">
                            <i class="fas fa-sign-in-alt"></i> Faça login para comprar
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="card" role="status">
                <h3>Nenhum produto disponível no momento</h3>
                <p>Estamos atualizando nosso catálogo. Volte em breve para conferir as novidades.</p>
            </div>
        @endforelse
    </div>
@endsection

@section('scripts')
<script>
    // Funcionalidade de busca/ordenação
    document.addEventListener('DOMContentLoaded', function() {
        const busca = document.getElementById('busca');
        const cards = Array.from(document.querySelectorAll('.produto-card'));

        cards.forEach(card => {
            card.dataset.originalDisplay = window.getComputedStyle(card).display || 'flex';
        });
        busca.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            cards.forEach(card => {
                const nome = card.querySelector('h3').textContent.toLowerCase();
                const descricao = card.querySelector('.descricao').textContent.toLowerCase();
                if (nome.includes(termo) || descricao.includes(termo)) {
                    card.style.display = card.dataset.originalDisplay || '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Ordenação
        const ordem = document.getElementById('ordem');
        const grid = document.querySelector('.produtos-grid');
        ordem.addEventListener('change', function() {
            const cardsArray = Array.from(document.querySelectorAll('.produto-card'));
            cardsArray.sort((a, b) => {
                switch(this.value) {
                    case 'nome':
                        return a.querySelector('h3').textContent.localeCompare(b.querySelector('h3').textContent);
                    case 'preco_asc':
                        return parseFloat(a.querySelector('.preco').textContent.replace('R$ ', '').replace('.', '').replace(',', '.')) -
                               parseFloat(b.querySelector('.preco').textContent.replace('R$ ', '').replace('.', '').replace(',', '.'));
                    case 'preco_desc':
                        return parseFloat(b.querySelector('.preco').textContent.replace('R$ ', '').replace('.', '').replace(',', '.')) -
                               parseFloat(a.querySelector('.preco').textContent.replace('R$ ', '').replace('.', '').replace(',', '.'));
                }
            });
            cardsArray.forEach(card => grid.appendChild(card));
        });
    });
</script>
@endsection