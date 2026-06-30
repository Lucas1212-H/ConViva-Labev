<?php

namespace App\Models;

use App\Support\StorageUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'conteudo',
        'imagem_url',
        'tipo',
        'link_externo',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function imagemUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => StorageUrl::publicUrl($value),
            set: fn (?string $value) => $value,
        );
    }
}
