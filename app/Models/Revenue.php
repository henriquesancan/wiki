<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int id Chave primária do registro de receita.
 * @property int extraction_id Chave estrangeira que referencia o registro de extração.
 * @property int company_id Chave estrangeira que referencia o registro da empresa.
 * @property int ranking Classificação da empresa.
 * @property float revenue Receita total associada à empresa.
 * @property float profit Lucro total associado à empresa.
 * @property float asset Total de ativos associados à empresa.
 * @property float value Valor de mercado associado à empresa.
 * @property Carbon created_at Data e hora em que o registro foi criado.
 * @property Carbon updated_at Data e hora da última atualização do registro.
 *
 * @property-read BelongsTo company() Relação com o modelo `Company`.
 * @property-read BelongsTo extraction() Relação com o modelo `Extraction`.
 *
 * @method static create(array $array) Cria um novo registro de receita com os atributos fornecidos.
 * @method static whereExtractionId($id)
 */
class Revenue extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'extraction_id',
        'company_id',
        'ranking',
        'revenue',
        'profit',
        'asset',
        'value',
    ];

    protected $table = 'revenues';

    /**
     * Obtém a empresa associada a este registro de receita.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Obtém a extração associada a este registro de receita.
     *
     * @return BelongsTo
     */
    public function extraction(): BelongsTo
    {
        return $this->belongsTo(Extraction::class);
    }
}
