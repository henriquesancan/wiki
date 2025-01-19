<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * Retorna o cabeçalho HTTP para respostas JSON com codificação UTF-8.
     *
     * @return array Retorna o array contendo o cabeçalho 'Content-Type'.
     */
    protected function headers(): array
    {
        return [
            'Content-Type' => 'application/json; charset=UTF-8'
        ];
    }

    /**
     * Gera uma resposta padrão para as operações.
     *
     * @param int $status Código de status HTTP da resposta.
     * @param bool $success Indica se a operação foi bem-sucedida ou não.
     *
     * @return object Retorna o array padrão de operações.
     */
    protected function response(int $status = Response::HTTP_INTERNAL_SERVER_ERROR, bool $success = false): object
    {
        return (object) [
            'status' => $status,
            'success' => $success,
            'errors' => [],
            'data' => [],
        ];
    }
}
