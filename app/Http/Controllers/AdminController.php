<?php

namespace App\Http\Controllers;

use App\Services\ProdutoService;
use App\Services\VendaService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    protected VendaService $vendaService;

    protected ProdutoService $produtoService;

    public function __construct(VendaService $vendaService, ProdutoService $produtoService)
    {
        $this->vendaService = $vendaService;
        $this->produtoService = $produtoService;
    }

    public function dashboard()
    {
        $cacheKey = 'admin_dashboard_metrics';
        $cacheDuration = 300; // 5 minutos

        $stats = Cache::remember($cacheKey, $cacheDuration, function () {
            return $this->vendaService->obterEstatisticas();
        });

        $topProdutos = collect();
        $recentes = collect();

        try {
            if (Schema::hasTable('vendas')) {
                $topProdutos = $this->vendaService->obterProdutosMaisVendidos(5);
                $recentes = $this->vendaService->obterVendasRecentes(5);
            }

            if ($topProdutos->isEmpty()) {
                $topProdutos = $this->produtoService->obterProdutosMaisCaros(5);
            }

        } catch (\Exception $e) {
            \Log::error('Erro ao carregar dados do dashboard: '.$e->getMessage());
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'topProdutos' => $topProdutos,
            'recentes' => $recentes,
        ]);
    }
}
