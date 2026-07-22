<?php

namespace App\Models;

use App\Support\StorageUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    protected $table = 'classes';

    protected $primaryKey = 'id_classe';

    protected $fillable = [
        'nome_cientifico',
        'nome_popular',
        'foto',
    ];

    public function especies(): HasMany
    {
        return $this->hasMany(Especie::class, 'id_classe', 'id_classe');
    }

    protected function foto(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => StorageUrl::publicUrl($value),
            set: fn (?string $value) => $value,
        );
    }
}
