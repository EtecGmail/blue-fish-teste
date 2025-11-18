<?php

namespace Tests\Unit;

use App\Domain\Vendas\Actions\ProcessarVendaAction;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProcessarVendaActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_processes_sale_and_updates_inventory(): void
    {
        Log::spy();

        $user = User::factory()->create();
        $produto = Produto::factory()->create([
            'preco' => 25.50,
            'estoque' => 20,
            'status' => 'ativo',
        ]);

        $action = new ProcessarVendaAction;
        $venda = $action->execute($user, $produto, 3);

        $this->assertEquals(76.50, (float) $venda->valor_total);
        $this->assertEquals($produto->id, $venda->produto_id);
        $this->assertEquals($user->id, $venda->user_id);
        $this->assertEquals(17, $produto->fresh()->estoque);

        Log::shouldHaveReceived('info')->withArgs(function (string $message, array $context) use ($venda) {
            return $message === 'Venda realizada com sucesso'
                && $context['venda_id'] === $venda->id
                && $context['quantidade'] === 3;
        });
    }

    public function test_it_validates_inactive_product(): void
    {
        $this->expectException(ValidationException::class);

        $user = User::factory()->create();
        $produto = Produto::factory()->create([
            'status' => 'inativo',
            'estoque' => 10,
        ]);

        $action = new ProcessarVendaAction;
        $action->execute($user, $produto, 1);
    }

    public function test_it_blocks_insufficient_inventory(): void
    {
        $this->expectException(ValidationException::class);

        $user = User::factory()->create();
        $produto = Produto::factory()->create([
            'estoque' => 1,
            'status' => 'ativo',
        ]);

        $action = new ProcessarVendaAction;
        $action->execute($user, $produto, 5);
    }
}
