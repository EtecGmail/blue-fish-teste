<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'teste@bluefish.com'],
            [
                'name' => 'Usuário Teste',
                'telefone' => '11999999999',
                'aceitou_termos_em' => now(),
                'password' => Hash::make('123456'),
                'is_admin' => false,
            ]
        );

        $adminEmail = env('ADMIN_USER_EMAIL', 'admin@bluefish.com');
        $adminPassword = env('ADMIN_USER_PASSWORD', 'admin123');
        $adminName = env('ADMIN_USER_NAME', 'Administrador Bluefish');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'telefone' => env('ADMIN_USER_PHONE', '11988887777'),
                'aceitou_termos_em' => now(),
                'password' => Hash::make($adminPassword),
                'is_admin' => true,
            ]
        );
    }
}
