<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectTest extends TestCase
{
    /**
     * Teste de coleta de dados.
     *
     * Este teste coleta os dados da página via WebDriver.
     */
    public function test_example(): void
    {
        $response = $this->post(route('collect.main'));

        $response->assertStatus(200);
    }
}
