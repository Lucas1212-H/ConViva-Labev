<?php

namespace App\Models;

use App\Support\StorageUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Especie extends Model
{
    protected $table = 'especies';

    protected $primaryKey = 'id_especie';

    protected $fillable = [
        'descricao',
        'nome_cientifico',
        'nome_popular',
        'foto',
        'id_classe',
        'id_ordem',
        'id_familia',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'id_classe', 'id_classe');
    }

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class, 'id_ordem', 'id_ordem');
    }

    public function familia(): BelongsTo
    {
        return $this->belongsTo(Familia::class, 'id_familia', 'id_familia');
    }

    public function ocorrencias(): HasMany
    {
        return $this->hasMany(Ocorrencia::class, 'especie_id', 'id_especie');
    }

    protected function foto(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => StorageUrl::publicUrl($value),
            set: fn (?string $value) => $value,
        );
    }
}
