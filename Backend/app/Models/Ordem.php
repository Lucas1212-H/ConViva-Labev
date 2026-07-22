<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ordem extends Model
{
    protected $table = 'ordens';

    protected $primaryKey = 'id_ordem';

    protected $fillable = [
        'nome_cientifico',
        'nome_popular',
        'id_classe',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'id_classe', 'id_classe');
    }

    public function familias(): HasMany
    {
        return $this->hasMany(Familia::class, 'id_ordem', 'id_ordem');
    }

    public function especies(): HasMany
    {
        return $this->hasMany(Especie::class, 'id_ordem', 'id_ordem');
    }
}
