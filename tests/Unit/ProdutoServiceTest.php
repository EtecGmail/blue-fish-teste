<?php

namespace Tests\Unit;

use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProdutoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProdutoService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ProdutoService;
    }

    public function test_atualizar_estoque_handles_entry_and_exit(): void
    {
        Log::spy();

        $produto = Produto::factory()->create([
            'estoque' => 10,
            'status' => 'ativo',
        ]);

        $updated = $this->service->atualizarEstoque($produto, 5, 'entrada', 'Reposição semanal');
        $this->assertEquals(15, $updated->estoque);

        $updated = $this->service->atualizarEstoque($updated, 4, 'saida', 'Pedido aprovado');
        $this->assertEquals(11, $updated->estoque);

        Log::shouldHaveReceived('info')->withArgs(function (string $message, array $context) {
            return $message === 'Movimentação de estoque'
                && $context['tipo'] === 'entrada'
                && $context['quantidade'] === 5;
        });
    }

    public function test_atualizar_estoque_throws_for_invalid_quantity(): void
    {
        $this->expectException(ValidationException::class);

        $produto = Produto::factory()->create(['estoque' => 10]);
        $this->service->atualizarEstoque($produto, 0);
    }

    public function test_obter_produtos_mais_caros_returns_sorted_payload(): void
    {
        Produto::factory()->create(['nome' => 'Tilápia', 'preco' => 25.00]);
        Produto::factory()->create(['nome' => 'Salmão', 'preco' => 120.00]);
        Produto::factory()->create(['nome' => 'Bacalhau', 'preco' => 90.00]);

        $resultados = $this->service->obterProdutosMaisCaros(2);

        $this->assertCount(2, $resultados);
        $this->assertEquals('Salmão', $resultados[0]['nome']);
        $this->assertEquals(120.00, $resultados[0]['valor_total']);
        $this->assertEquals('Bacalhau', $resultados[1]['nome']);
    }
}
