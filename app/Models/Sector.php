<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int id Chave primária do registro do setor.
 * @property string name Nome do setor.
 * @property Carbon created_at Data e hora em que o registro foi criado.
 * @property Carbon updated_at Data e hora da última atualização do registro.
 *
 * @method static firstOrNew(array $array)
 */
class Sector extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
    ];

    protected $table = 'sectors';
}
