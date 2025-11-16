<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->unique()->words(2, true),
            'descricao' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 500),
            'imagem' => null,
            'categoria' => $this->faker->randomElement(['Peixes', 'Frutos do mar', 'Frios']),
            'estoque' => $this->faker->numberBetween(5, 120),
            'status' => 'ativo',
        ];
    }
}
