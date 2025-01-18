<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int id Chave primária do registro da extração.
 * @property string url URL da página de extração.
 * @property Carbon created_at Data e hora em que o registro foi criado.
 * @property Carbon updated_at Data e hora da última atualização do registro.
 *
 * @method static create(array $array)
 */
class Extraction extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'url',
    ];

    protected $table = 'extractions';
}
