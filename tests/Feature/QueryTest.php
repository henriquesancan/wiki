<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QueryTest extends TestCase
{
    /**
     * Teste de solicitação válida com a regra 'greater'.
     *
     * Este teste verifica se a API retorna o status 200 OK
     * quando a `rule` é 'greater' e o parâmetro `billions` é fornecido.
     */
    public function test_valid_request_with_rule_greater(): void
    {
        $response = $this->get(route('revenue.show', [
            'rule' => 'greater',
            'billions' => '10',
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Teste de solicitação inválida com a regra 'greater'.
     *
     * Este teste verifica se a API retorna o status 422 Unprocessable Entity
     * quando a `rule` é 'greater', mas o parâmetro `billions` está ausente.
     */
    public function test_invalid_request_with_rule_greater(): void
    {
        $response = $this->get(route('revenue.show', [
            'rule' => 'greater',
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Teste de solicitação válida com a regra 'smaller'.
     *
     * Este teste verifica se a API retorna o status 200 OK
     * quando a `rule` é 'smaller' e o parâmetro `billions` é fornecido.
     */
    public function test_valid_request_with_rule_smaller(): void
    {
        $response = $this->get(route('revenue.show', [
            'rule' => 'smaller',
            'billions' => '10',
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Teste de solicitação inválida com a regra 'smaller'.
     *
     * Este teste verifica se a API retorna o status 422 Unprocessable Entity
     * quando a `rule` é 'smaller', mas o parâmetro `billions` está ausente.
     */
    public function test_invalid_request_with_rule_smaller(): void
    {
        $response = $this->get(route('revenue.show', [
            'rule' => 'smaller',
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Teste de solicitação válida com a regra 'between'.
     *
     * Este teste verifica se a API retorna o status 200 OK
     * quando a `rule` é 'between' e o parâmetro `range` é fornecido com valores válidos.
     */
    public function test_valid_request_with_rule_between(): void
    {
        $response = $this->get(route('revenue.show', [
            'rule' => 'between',
            'range' => ['10', '20'],
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Teste de solicitação inválida com a regra 'between'.
     *
     * Este teste verifica se a API retorna o status 422 Unprocessable Entity
     * quando a `rule` é 'between', mas o parâmetro `range` está ausente.
     */
    public function test_invalid_request_with_rule_between(): void
    {
        $response = $this->get(route('revenue.show', [
            'rule' => 'between',
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
    }
}
