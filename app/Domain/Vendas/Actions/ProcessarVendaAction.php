<?php

namespace App\Domain\Vendas\Actions;

use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProcessarVendaAction
{
    public function execute(User $user, Produto $produto, int $quantidade, array $dadosAdicionais = []): Venda
    {
        $this->validarDisponibilidadeProduto($produto, $quantidade);

        $valorTotal = $this->calcularValorTotal($produto, $quantidade);

        return DB::transaction(function () use ($user, $produto, $quantidade, $valorTotal, $dadosAdicionais) {
            try {
                $venda = $this->criarVenda($user, $produto, $quantidade, $valorTotal, $dadosAdicionais);

                $this->atualizarEstoque($produto, $quantidade);

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

    private function validarDisponibilidadeProduto(Produto $produto, int $quantidade): void
    {
        if ($produto->status !== 'ativo') {
            throw ValidationException::withMessages([
                'produto' => 'Produto indisponível para venda.'
            ]);
        }

        if ($produto->estoque < $quantidade) {
            throw ValidationException::withMessages([
                'quantidade' => sprintf(
                    'Quantidade solicitada (%d) maior que estoque disponível (%d).',
                    $quantidade,
                    $produto->estoque
                )
            ]);
        }

        if ($quantidade <= 0) {
            throw ValidationException::withMessages([
                'quantidade' => 'Quantidade deve ser maior que zero.'
            ]);
        }
    }

    private function calcularValorTotal(Produto $produto, int $quantidade): float
    {
        $valorUnitario = $produto->preco;
        $valorTotal = $valorUnitario * $quantidade;

        return round($valorTotal, 2);
    }

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

    private function atualizarEstoque(Produto $produto, int $quantidade): void
    {
        $produto->decrement('estoque', $quantidade);

        if ($produto->estoque <= $produto->estoque_minimo ?? 10) {
            $this->notificarEstoqueBaixo($produto);
        }
    }

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

    private function notificarEstoqueBaixo(Produto $produto): void
    {
        Log::warning('Estoque baixo detectado', [
            'produto_id' => $produto->id,
            'produto_nome' => $produto->nome,
            'estoque_atual' => $produto->estoque,
            'estoque_minimo' => $produto->estoque_minimo ?? 10,
        ]);
    }
}
