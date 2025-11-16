<?php

namespace Tests\Feature;

use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_registra_venda(): void
    {
        $user = User::factory()->create();
        $produto = Produto::factory()->create(['preco' => 50]);

        $response = $this->actingAs($user)->post(route('vendas.store'), [
            'produto_id' => $produto->id,
            'quantidade' => 3,
        ]);

        $response->assertRedirect(route('vendas.index'));

        $this->assertDatabaseHas('vendas', [
            'user_id' => $user->id,
            'produto_id' => $produto->id,
            'quantidade' => 3,
            'valor_total' => 150.00,
        ]);
    }

    public function test_hospede_nao_consegue_registrar_venda(): void
    {
        $produto = Produto::factory()->create();

        $response = $this->post(route('vendas.store'), [
            'produto_id' => $produto->id,
            'quantidade' => 1,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('vendas', 0);
    }
}
