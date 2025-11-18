<?php

namespace App\Http\Controllers;

use App\Services\ProdutoService;
use Illuminate\Routing\Controller;

class ProdutoController extends Controller
{
    protected ProdutoService $produtoService;

    public function __construct(ProdutoService $produtoService)
    {
        $this->produtoService = $produtoService;
    }

    /**
     * Lista todos os produtos (para a página /produtos).
     */
    public function index()
    {
        $produtos = $this->produtoService->obterProdutosAtivos([
            'ordenar_por' => 'nome',
            'direcao' => 'asc',
        ]);

        return view('produtos.index', compact('produtos'));
    }

    /**
     * Mostra os detalhes de um produto (para /produto/{id}).
     */
    public function show($id)
    {
        try {
            $produto = $this->produtoService->buscarProdutoAtivo($id);

            return view('produtos.show', compact('produto'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('produtos.index')
                ->with('erro', 'Produto não encontrado ou indisponível.');
        } catch (\Exception $e) {
            return redirect()
                ->route('produtos.index')
                ->with('erro', 'Ocorreu um erro ao carregar o produto.');
        }
    }
}
