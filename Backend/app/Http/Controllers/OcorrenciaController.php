<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OcorrenciaController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'denunciante_nome'    => 'required|string|max:255',
            'denunciante_contato_tipo'  => 'required|string',
            'denunciante_contato_valor' => 'required|string',
            'categoria_ocorrencia'=> 'required|string',
            'tipo_animal'         => 'required|string',
            'situacao_animal'     => 'required|string',
            'descricao'           => 'required|string',
            'latitude'            => 'required|numeric',
            'longitude'           => 'required|numeric',
            'ponto_referencia'    => 'required|string',
            'foto'                => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('ocorrencias', 'public');
        }

        $ocorrencia = Ocorrencia::create([
            'denunciante_nome'          => $request->denunciante_nome,
            'denunciante_contato_tipo'  => $request->denunciante_contato_tipo,
            'denunciante_contato_valor' => $request->denunciante_contato_valor,
            'codigo_ocorrencia'         => strtoupper(Str::random(6)),
            'categoria_ocorrencia'      => $request->categoria_ocorrencia,
            'tipo_animal'               => $request->tipo_animal,
            'local_ocorrencia'          => $request->situacao_animal,
            'descricao_ocorrencia'      => $request->descricao,
            'latitude'                  => $request->latitude,
            'longitude'                 => $request->longitude,
            'ponto_referencia'          => $request->ponto_referencia,
            'foto_path'                 => $fotoPath, 
            'status'                    => 'Pendente', 
        ]);

        return response()->json([
            'message' => 'Ocorrência registrada com sucesso!',
            'codigo' => $ocorrencia->codigo_ocorrencia,
            'data' => $ocorrencia
        ], 201);
    }
}