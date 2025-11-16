<?php

namespace App\Services;

use App\Models\Produto;
use App\Models\Venda;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Serviço de Vendas - Responsável pela lógica de negócio relacionada a vendas
 * 
 * Principios SOLID aplicados:
 * - Single Responsibility Principle (SRP): Classe tem uma única razão para mudar
 * - Dependency Inversion Principle (DIP): Depende de abstrações, não de implementações concretas
 * 
 * @package App\Services
 */
class VendaService
{
    /**
     * Processa uma nova venda com todas as validações e atualizações necessárias
     *
     * @param User $user
     * @param Produto $produto
     * @param int $quantidade
     * @param array $dadosAdicionais
     * @return Venda
     * @throws ValidationException
     * @throws \Exception
     */
    public function processarVenda(User $user, Produto $produto, int $quantidade, array $dadosAdicionais = []): Venda
    {
        // Validar disponibilidade do produto
        $this->validarDisponibilidadeProduto($produto, $quantidade);
        
        // Calcular valores
        $valorTotal = $this->calcularValorTotal($produto, $quantidade);
        
        // Processar venda em transação
        return DB::transaction(function () use ($user, $produto, $quantidade, $valorTotal, $dadosAdicionais) {
            try {
                // Criar a venda
                $venda = $this->criarVenda($user, $produto, $quantidade, $valorTotal, $dadosAdicionais);
                
                // Atualizar estoque
                $this->atualizarEstoque($produto, $quantidade);
                
                // Registrar log da operação
                $this->registrarLogVenda($venda, $user, $produto, $quantidade);
                
                return $venda;
                
            } catch (\Exception $e) {
                Log::error('Erro ao processar venda', [
                    'user_id' => $user->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $quantidade,
                    'erro' => $e->getMessage()
                ]);
                
                throw $e;
            }
        });
    }

    /**
     * Valida se o produto está disponível para venda
     *
     * @param Produto $produto
     * @param int $quantidade
     * @throws ValidationException
     */
    private function validarDisponibilidadeProduto(Produto $produto, int $quantidade): void
    {
        // Verificar se o produto está ativo
        if ($produto->status !== 'ativo') {
            throw ValidationException::withMessages([
                'produto' => 'Produto indisponível para venda.'
            ]);
        }

        // Verificar estoque
        if ($produto->estoque < $quantidade) {
            throw ValidationException::withMessages([
                'quantidade' => sprintf(
                    'Quantidade solicitada (%d) maior que estoque disponível (%d).',
                    $quantidade,
                    $produto->estoque
                )
            ]);
        }

        // Verificar quantidade mínima
        if ($quantidade <= 0) {
            throw ValidationException::withMessages([
                'quantidade' => 'Quantidade deve ser maior que zero.'
            ]);
        }
    }

    /**
     * Calcula o valor total da venda
     *
     * @param Produto $produto
     * @param int $quantidade
     * @return float
     */
    private function calcularValorTotal(Produto $produto, int $quantidade): float
    {
        $valorUnitario = $produto->preco;
        $valorTotal = $valorUnitario * $quantidade;
        
        // Aplicar descontos ou promoções futuras aqui
        // $valorTotal = $this->aplicarDescontos($valorTotal, $produto, $quantidade);
        
        return round($valorTotal, 2);
    }

    /**
     * Cria o registro da venda
     *
     * @param User $user
     * @param Produto $produto
     * @param int $quantidade
     * @param float $valorTotal
     * @param array $dadosAdicionais
     * @return Venda
     */
    private function criarVenda(User $user, Produto $produto, int $quantidade, float $valorTotal, array $dadosAdicionais): Venda
    {
        return Venda::create([
            'user_id' => $user->id,
            'produto_id' => $produto->id,
            'quantidade' => $quantidade,
            'valor_total' => $valorTotal,
            'status' => $dadosAdicionais['status'] ?? 'pendente',
            'observacoes' => $dadosAdicionais['observacoes'] ?? null,
        ]);
    }

    /**
     * Atualiza o estoque do produto
     *
     * @param Produto $produto
     * @param int $quantidade
     * @return void
     */
    private function atualizarEstoque(Produto $produto, int $quantidade): void
    {
        // Usar decrement para evitar race conditions
        $produto->decrement('estoque', $quantidade);
        
        // Verificar se atingiu estoque baixo
        if ($produto->estoque <= $produto->estoque_minimo ?? 10) {
            $this->notificarEstoqueBaixo($produto);
        }
    }

    /**
     * Registra log da operação de venda
     *
     * @param Venda $venda
     * @param User $user
     * @param Produto $produto
     * @param int $quantidade
     * @return void
     */
    private function registrarLogVenda(Venda $venda, User $user, Produto $produto, int $quantidade): void
    {
        Log::info('Venda realizada com sucesso', [
            'venda_id' => $venda->id,
            'user_id' => $user->id,
            'produto_id' => $produto->id,
            'produto_nome' => $produto->nome,
            'quantidade' => $quantidade,
            'valor_total' => $venda->valor_total,
            'estoque_anterior' => $produto->estoque + $quantidade,
            'estoque_atual' => $produto->estoque,
        ]);
    }

    /**
     * Notifica sobre estoque baixo
     *
     * @param Produto $produto
     * @return void
     */
    private function notificarEstoqueBaixo(Produto $produto): void
    {
        Log::warning('Estoque baixo detectado', [
            'produto_id' => $produto->id,
            'produto_nome' => $produto->nome,
            'estoque_atual' => $produto->estoque,
            'estoque_minimo' => $produto->estoque_minimo ?? 10,
        ]);
        
        // TODO: Implementar notificação por email/SMS para administradores
    }

    /**
     * Cancela uma venda e restaura o estoque
     *
     * @param Venda $venda
     * @return bool
     * @throws \Exception
     */
    public function cancelarVenda(Venda $venda): bool
    {
        if ($venda->status === 'cancelado') {
            throw ValidationException::withMessages([
                'venda' => 'Venda já está cancelada.'
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
                    'erro' => $e->getMessage()
                ]);
                
                throw $e;
            }
        });
    }

    /**
     * Obtém estatísticas de vendas
     *
     * @param User|null $user
     * @param array $filtros
     * @return array
     */
    public function obterEstatisticas(?User $user = null, array $filtros = []): array
    {
        $query = Venda::query();
        
        if ($user) {
            $query->where('user_id', $user->id);
        }
        
        // Aplicar filtros adicionais
        if (!empty($filtros['data_inicio'])) {
            $query->where('created_at', '>=', $filtros['data_inicio']);
        }
        
        if (!empty($filtros['data_fim'])) {
            $query->where('created_at', '<=', $filtros['data_fim']);
        }
        
        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }
        
        return [
            'usuarios' => \App\Models\User::count(),
            'produtos' => \App\Models\Produto::count(),
            'contatos' => (\App\Models\Contato::count() ?? 0),
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
     * @param User $user
     * @param int $limite
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obterVendasPorUsuario(User $user, int $limite = null)
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
     * @param int $limite
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
            ->get()
            ->map(function ($item) {
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
     * @param int $limite
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