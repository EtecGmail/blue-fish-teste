<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('convidado deve ser redirecionado da dashboard admin para a página de login', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login.form'));
});

test('usuário autenticado não admin não pode acessar a dashboard admin', function () {
    // Cria um usuário comum (não admin)
    $user = User::factory()->create();

    // Tenta acessar a dashboard como o usuário comum
    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    // Espera um status "Forbidden"
    $response->assertForbidden();
});

test('usuário admin autenticado pode acessar a dashboard admin', function () {
    // Cria um usuário admin
    $admin = User::factory()->admin()->create();

    // Tenta acessar a dashboard como o admin
    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    // Espera um status "OK" e a visualização da view correta
    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
});
