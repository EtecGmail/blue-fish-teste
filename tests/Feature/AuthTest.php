<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_and_css_load(): void
    {
        $this->get('/')->assertOk();
        $this->assertFileExists(public_path('css/styles.css'));
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'senha' => 'secret',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_login_succeeds_and_access_protected_route(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'senha' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $this->get('/produtos')->assertOk();
    }

    public function test_protected_route_redirects_guests(): void
    {
        $this->get('/produtos')->assertRedirect('/login');
    }

    public function test_logout_invalidates_session(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'senha' => 'password',
        ]);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->get('/produtos')->assertRedirect('/login');
    }
}
