<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ConvertMoneyTrait
{
    protected const BILLION = 1e9;
    protected const MILLION = 1e6;

    /**
     * Converte um valor para float.
     *
     * @param float $value O valor a ser convertido para float.
     *
     * @return string O valor convertido em float.
     */
    protected function convertToFloat(float $value): string
    {
        $value = str_replace(',', '.', $value);

        if (str_contains($value, 'milhões')) {
            $value = (float) str_replace('milhões', '', $value) * self::MILLION;
        } else {
            $value = (float) $value * self::BILLION;
        }

        return $value;
    }

    /**
     * Converte um valor para string.
     *
     * @param float $value O valor a ser convertido para string.
     *
     * @return string O valor convertido em string.
     */
    protected function convertToString(float $value): string
    {
        $value = round($value / self::BILLION, 2);

        return Str::finish($value, ' bilhões');
    }
}
