<?php

namespace App\Http\Resources;

use App\Traits\ConvertMoneyTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class RevenueResource extends JsonResource
{
    use ConvertMoneyTrait;

    /**
     * Transforma o recurso em um array.
     *
     * @param Request $request A instância da requisição.
     *
     * @return array<string, mixed> Um array contendo os dados do recurso.
     */
    public function toArray(Request $request): array
    {
        return [
            'company' => Str::title($this->company->name),
            'profit' => $this->convertToString($this->profit),
            'rank' => $this->ranking,
        ];
    }
}
