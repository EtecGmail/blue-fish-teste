<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class UiButtonTest extends TestCase
{
    public function test_render_button_without_full_width(): void
    {
        $html = Blade::render('<x-ui.button>Entrar</x-ui.button>');

        $this->assertStringContainsString('class="btn', $html);
        $this->assertStringNotContainsString('btn--full-width', $html);
    }

    public function test_render_button_with_full_width_true(): void
    {
        $html = Blade::render('<x-ui.button :full-width="true">Entrar</x-ui.button>');

        $this->assertStringContainsString('btn--full-width', $html);
    }
}