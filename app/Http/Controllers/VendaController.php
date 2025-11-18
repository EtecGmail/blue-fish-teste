<?php

namespace App\Http\Controllers;

use App\Domain\Vendas\Actions\ProcessarVendaAction;
use App\Http\Requests\VendaStoreRequest;
use App\Services\ProdutoService;
use App\Services\VendaService;
use Illuminate\Routing\Controller;

class VendaController extends Controller
{
    protected VendaService $vendaService;

    protected ProdutoService $produtoService;

    public function __construct(VendaService $vendaService, ProdutoService $produtoService)
    {
        $this->vendaService = $vendaService;
        $this->produtoService = $produtoService;
    }

    public function index()
    {
        $vendas = $this->vendaService->obterVendasPorUsuario(auth()->user());

        return view('vendas.index', compact('vendas'));
    }

    public function store(VendaStoreRequest $request, ProcessarVendaAction $processarVenda)
    {
        try {
            $validated = $request->validated();

            $produto = $this->produtoService->buscarProdutoAtivo($validated['produto_id']);

            $venda = $processarVenda->execute(
                auth()->user(),
                $produto,
                $validated['quantidade']
            );

            return redirect()
                ->route('vendas.index')
                ->with('sucesso', 'Compra registrada com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->with('erro', $e->getMessage());

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('erro', 'Ocorreu um erro ao processar sua compra. Por favor, tente novamente.');
        }
    }
}
