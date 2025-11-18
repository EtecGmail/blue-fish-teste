<?php

namespace App\Services;

use App\Models\User;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Serviço de Vendas - Responsável pela lógica de negócio relacionada a vendas
 *
 * Principios SOLID aplicados:
 * - Single Responsibility Principle (SRP): Classe tem uma única razão para mudar
 * - Dependency Inversion Principle (DIP): Depende de abstrações, não de implementações concretas
 */
class VendaService
{
    /**
     * Cancela uma venda e restaura o estoque
     *
     * @throws \Exception
     */
    public function cancelarVenda(Venda $venda): bool
    {
        if ($venda->status === 'cancelado') {
            throw ValidationException::withMessages([
                'venda' => 'Venda já está cancelada.',
            ]);
        }

        return DB::transaction(function () use ($venda) {
            try {
                // Restaurar estoque
                $venda->produto->increment('estoque', $venda->quantidade);

                // Atualizar status
                $venda->update(['status' => 'cancelado']);

                // Registrar log
                Log::info('Venda cancelada', [
                    'venda_id' => $venda->id,
                    'produto_id' => $venda->produto_id,
                    'quantidade_restaurada' => $venda->quantidade,
                    'estoque_atual' => $venda->produto->estoque,
                ]);

                return true;

            } catch (\Exception $e) {
                Log::error('Erro ao cancelar venda', [
                    'venda_id' => $venda->id,
                    'erro' => $e->getMessage(),
                ]);

                throw $e;
            }
        });
    }

    /**
     * Obtém estatísticas de vendas
     */
    public function obterEstatisticas(?User $user = null, array $filtros = []): array
    {
        $query = Venda::query();

        if ($user) {
            $query->where('user_id', $user->id);
        }

        // Aplicar filtros adicionais
        if (! empty($filtros['data_inicio'])) {
            $query->where('created_at', '>=', $filtros['data_inicio']);
        }

        if (! empty($filtros['data_fim'])) {
            $query->where('created_at', '<=', $filtros['data_fim']);
        }

        if (! empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        return [
            'usuarios' => \App\Models\User::count(),
            'produtos' => \App\Models\Produto::count(),
            'contatos' => \App\Models\Contato::count(),
            'vendas' => $query->count(),
            'faturamento' => $query->sum('valor_total'),
            'total_vendas' => $query->count(),
            'valor_total' => $query->sum('valor_total'),
            'media_valor' => $query->avg('valor_total'),
            'vendas_por_status' => $query->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
        ];
    }

    /**
     * Obtém as vendas de um usuário específico
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obterVendasPorUsuario(User $user, ?int $limite = null)
    {
        $query = Venda::with('produto')
            ->where('user_id', $user->id)
            ->latest();

        if ($limite) {
            $query->limit($limite);
        }

        return $query->get();
    }

    /**
     * Obtém os produtos mais vendidos
     *
     * @return \Illuminate\Support\Collection
     */
    public function obterProdutosMaisVendidos(int $limite = 5)
    {
        return Venda::query()
            ->selectRaw('produtos.nome, SUM(vendas.quantidade) as quantidade_total, SUM(vendas.valor_total) as valor_total')
            ->join('produtos', 'vendas.produto_id', '=', 'produtos.id')
            ->groupBy('produtos.id', 'produtos.nome')
            ->orderByDesc('quantidade_total')
            ->limit($limite)
            ->toBase()
            ->get()
            ->map(function (\stdClass $item): array {
                return [
                    'nome' => $item->nome,
                    'quantidade' => (int) $item->quantidade_total,
                    'valor_total' => (float) $item->valor_total,
                ];
            });
    }

    /**
     * Obtém as vendas mais recentes
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obterVendasRecentes(int $limite = 5)
    {
        return Venda::with(['produto', 'user'])
            ->orderByDesc('created_at')
            ->limit($limite)
            ->get();
    }
}
