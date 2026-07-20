<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $table = 'ocorrencias';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'denunciante_nome',
        'denunciante_contato_tipo',
        'denunciante_contato_valor',
        'distincao_biologica',
        'tipo_animal',
        'situacao_animal',
        'descricao_ocorrencia',
        'latitude',
        'longitude',
        'ponto_referencia',
        'descricao_local_exato',
        'foto_path',
        'video_path',
        'status',
        'publicado_no_mapa',
        'parecer_tecnico',
        'assigned_to',
        'habitat',
        'microhabitat',
        'classe',
        'ordem',
        'familia',
        'especie',
        'que_coletou',
        'destino',
        'registrado_por',
        'classe_etaria',
        'tempo',
        'campo_encontrado',
        'observacoes',
        'codigo_registro',
    ];
}
