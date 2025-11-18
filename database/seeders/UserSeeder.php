<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cria um usuário de teste usando a factory para garantir dados anônimos e consistentes.
        User::factory()->create([
            'name' => 'Usuário de Teste',
            'email' => 'teste@bluefish.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
        ]);

        $adminEmail = config('admin.user_email');
        $adminPassword = config('admin.user_password');
        $adminName = config('admin.user_name');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'telefone' => config('admin.user_phone'),
                'aceitou_termos_em' => now(),
                'password' => Hash::make($adminPassword),
                'is_admin' => true,
            ]
        );
    }
}
