<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    public function test_privacidade_page_is_accessible_and_contains_minimum_content(): void
    {
        $response = $this->get('/privacidade');
        $response->assertStatus(200);
        $response->assertSee('Política de Privacidade', false);
        $response->assertSee('LGPD', false);
        $response->assertSee('contato@bluefish.com', false);
    }

    public function test_termos_page_is_accessible_and_contains_minimum_content(): void
    {
        $response = $this->get('/termos');
        $response->assertStatus(200);
        $response->assertSee('Termos de Uso', false);
        $response->assertSee('Marco Civil da Internet', false);
        $response->assertSee('contato@bluefish.com', false);
    }
}
