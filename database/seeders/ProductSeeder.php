<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'nome' => 'Salmão Fresco',
                'descricao' => 'Salmão do Atlântico',
                'preco' => 49.90,
                'imagem' => 'img/salmao.jpg',
            ],
            [
                'nome' => 'Atum',
                'descricao' => 'Atum fresco',
                'preco' => 39.90,
                'imagem' => 'storage/img/atum.jpg', // Corrigido para apontar para a imagem
            ],
            [
                'nome' => 'Camarão',
                'descricao' => 'Camarão rosa grande',
                'preco' => 59.90,
                'imagem' => 'img/camarao.jpg',
            ],
            [
                'nome' => 'Lula',
                'descricao' => 'Lula fresca',
                'preco' => 29.90,
                'imagem' => 'img/lula.jpg',
            ],
            [
                'nome' => 'Polvo',
                'descricao' => 'Polvo inteiro limpo',
                'preco' => 79.90,
                'imagem' => 'img/polvo.jpg',
            ],
            [
                'nome' => 'Produto sem imagem',
                'descricao' => 'Produto genérico sem imagem',
                'preco' => 19.90,
                'imagem' => null,
            ],
            [
                'nome' => 'Salmão',
                'descricao' => 'Salmão do Atlântico',
                'preco' => 15.00,
                'imagem' => 'storage/img/salmao.png', // Padronize também este caminho
            ],
        ];

        foreach ($items as $item) {
            Produto::updateOrCreate(['nome' => $item['nome']], $item);
        }
    }
}
