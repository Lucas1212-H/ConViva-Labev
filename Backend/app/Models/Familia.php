<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Familia extends Model
{
    protected $table = 'familias';

    protected $primaryKey = 'id_familia';

    protected $fillable = [
        'nome_cientifico',
        'nome_popular',
        'id_ordem',
    ];

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class, 'id_ordem', 'id_ordem');
    }

    public function especies(): HasMany
    {
        return $this->hasMany(Especie::class, 'id_familia', 'id_familia');
    }
}
