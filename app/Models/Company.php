<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int id Chave primária do registro da empresa.
 * @property int sector_id Chave estrangeira que referencia o registro do setor.
 * @property string name Nome da empresa.
 * @property Carbon created_at Data e hora em que o registro foi criado.
 * @property Carbon updated_at Data e hora da última atualização do registro.
 *
 * @property-read BelongsTo sector() Relação com o modelo `Sector`.
 *
 * @method static firstOrNew(array $array)
 */
class Company extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'sector_id',
        'name',
    ];

    protected $table = 'companies';

    /**
     * Obtém o setor associada a este registro de empresa.
     *
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
