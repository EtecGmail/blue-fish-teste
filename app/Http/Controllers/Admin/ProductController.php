<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProdutoRequest;
use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected ProdutoService $produtoService;

    public function __construct(ProdutoService $produtoService)
    {
        $this->produtoService = $produtoService;
    }

    public function index(Request $request): View
    {
        $query = Produto::query();

        if ($request->filled('status') && $request->status !== 'todos') {
            $query->where('status', $request->status);
        }

        if ($request->filled('busca')) {
            $busca = $request->string('busca')->trim()->toString();
            if ($busca === '') {
                $busca = null;
            }

            if ($busca) {
                $query->where(function ($inner) use ($busca) {
                    $inner->where('nome', 'like', "%{$busca}%")
                        ->orWhere('descricao', 'like', "%{$busca}%")
                        ->orWhere('categoria', 'like', "%{$busca}%");
                });
            }
        }

        $produtos = $query->orderBy('nome')->paginate(10)->withQueryString();

        return view('admin.products.index', [
            'produtos' => $produtos,
            'filtros' => [
                'status' => $request->input('status', 'todos'),
                'busca' => $request->filled('busca') ? trim((string) $request->input('busca')) : '',
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'produto' => new Produto([
                'status' => 'ativo',
                'estoque' => 0,
            ]),
        ]);
    }

    public function store(ProdutoRequest $request): RedirectResponse
    {
        try {
            $dados = $request->validated();
            $this->produtoService->criarProduto($dados);

            return redirect()
                ->route('admin.products.index')
                ->with('sucesso', 'Produto cadastrado com sucesso!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('erro', 'Ocorreu um erro ao criar o produto. Por favor, tente novamente.');
        }
    }

    public function edit(Produto $produto): View
    {
        return view('admin.products.edit', compact('produto'));
    }

    public function update(ProdutoRequest $request, Produto $produto): RedirectResponse
    {
        try {
            $dados = $request->validated();
            $this->produtoService->atualizarProduto($produto, $dados);

            return redirect()
                ->route('admin.products.index')
                ->with('sucesso', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('erro', 'Ocorreu um erro ao atualizar o produto. Por favor, tente novamente.');
        }
    }

    public function destroy(Produto $produto): RedirectResponse
    {
        try {
            $this->produtoService->removerProduto($produto);

            return redirect()
                ->route('admin.products.index')
                ->with('sucesso', 'Produto removido com sucesso.');

        } catch (\Exception $e) {
            return back()
                ->with('erro', $e->getMessage() ?: 'Ocorreu um erro ao remover o produto.');
        }
    }
}
