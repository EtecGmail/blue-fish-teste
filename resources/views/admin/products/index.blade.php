@extends('layouts.admin')

@section('title', 'Produtos - Painel Bluefish')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Gerenciamento de produtos</h1>
            <p class="admin-page-subtitle">Atualize catálogo, estoque e status para garantir a melhor experiência do cliente.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Cadastrar produto</a>
    </div>

    <section class="card" aria-labelledby="filtros-produtos">
        <header class="card-header" id="filtros-produtos">
            <h2>Filtros</h2>
        </header>
        <form method="GET" class="filters" role="search">
            <div class="filters-grid">
                <div class="form-field">
                    <label for="busca">Buscar</label>
                    <input type="search" id="busca" name="busca" value="{{ $filtros['busca'] }}" placeholder="Nome, descrição ou categoria">
                </div>
                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="todos" @selected($filtros['status'] === 'todos')>Todos</option>
                        <option value="ativo" @selected($filtros['status'] === 'ativo')>Ativos</option>
                        <option value="inativo" @selected($filtros['status'] === 'inativo')>Inativos</option>
                    </select>
                </div>
            </div>
            <div class="filters-actions">
                <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </section>

    <section class="card" aria-labelledby="lista-produtos">
        <header class="card-header" id="lista-produtos">
            <h2>Produtos cadastrados</h2>
            <p role="status">{{ $produtos->total() }} itens encontrados</p>
        </header>
        <div class="table-responsive">
            <table class="table" role="table">
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Estoque</th>
                        <th scope="col">Status</th>
                        <th scope="col">Atualizado em</th>
                        <th scope="col" class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $produto)
                        <tr>
                            <th scope="row">
                                <div class="table-product">
                                    <div class="table-product__thumb" aria-hidden="true">
                                        <img src="{{ $produto->imagem_url }}" alt="" loading="lazy">
                                    </div>
                                    <div>
                                        <p class="table-product__name">{{ $produto->nome }}</p>
                                        <p class="table-product__category">{{ $produto->categoria ?: 'Sem categoria' }}</p>
                                    </div>
                                </div>
                            </th>
                            <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                            <td>{{ $produto->estoque ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $produto->status === 'ativo' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($produto->status) }}
                                </span>
                            </td>
                            <td>{{ optional($produto->updated_at)->format('d/m/Y H:i') }}</td>
                            <td class="text-right">
                                <div class="table-actions">
                                    @if($produto->status === 'ativo')
                                        <a class="btn btn-secondary btn-sm" href="{{ route('produto.show', $produto) }}">Ver</a>
                                    @endif
                                    <a class="btn btn-primary btn-sm" href="{{ route('admin.products.edit', $produto) }}">Editar</a>
                                    <form action="{{ route('admin.products.destroy', $produto) }}" method="POST" onsubmit="return confirm('Deseja remover este produto? Esta ação não pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Nenhum produto cadastrado. Clique em "Cadastrar produto" para adicionar o primeiro item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($produtos->hasPages())
            <nav class="pagination" aria-label="Paginação de produtos">
                @if($produtos->previousPageUrl())
                    <a class="btn btn-secondary btn-sm" href="{{ $produtos->previousPageUrl() }}">Anterior</a>
                @else
                    <span class="btn btn-secondary btn-sm" aria-disabled="true" role="link" tabindex="-1">Anterior</span>
                @endif
                <span class="pagination__info">Página {{ $produtos->currentPage() }} de {{ $produtos->lastPage() }}</span>
                @if($produtos->nextPageUrl())
                    <a class="btn btn-secondary btn-sm" href="{{ $produtos->nextPageUrl() }}">Próxima</a>
                @else
                    <span class="btn btn-secondary btn-sm" aria-disabled="true" role="link" tabindex="-1">Próxima</span>
                @endif
            </nav>
        @endif
    </section>
@endsection
