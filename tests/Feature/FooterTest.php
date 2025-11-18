<?php

namespace Tests\Feature;

use Tests\TestCase;

class FooterTest extends TestCase
{
    public function test_footer_sections_are_present_on_home(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('Bluefish', false);
        $response->assertSee('Links úteis', false);
        $response->assertSee('Contato', false);
        $response->assertSee('Redes sociais', false);

        $response->assertSee('CNPJ: 00.000.000/0001-00', false);
        $response->assertSee('contato@bluefish.com', false);

        $response->assertSee('Política de Privacidade', false);
        $response->assertSee('Termos de Uso', false);
        $response->assertSee('LGPD', false);

        $response->assertSee('aria-label="Facebook da Bluefish"', false);
        $response->assertSee('aria-label="Instagram da Bluefish"', false);
        $response->assertSee('aria-label="Twitter da Bluefish"', false);
        $response->assertSee('aria-label="LinkedIn da Bluefish"', false);

        $response->assertSee('class="footer"', false);
        $response->assertSee('class="footer__container"', false);
        $response->assertSee('class="footer__bottom"', false);
    }
}
