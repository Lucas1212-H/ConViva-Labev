<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    public function index()
    {
        return response()->json(
            Classe::withCount('especies')
                ->orderBy('nome_popular')
                ->get()
        );
    }

    public function show(int $id)
    {
        $classe = Classe::with([
            'especies' => function ($query) {
                $query->orderBy('nome_popular');
            },
            'especies.ocorrencias' => function ($query) {
                $query->select('id', 'especie_id', 'latitude', 'longitude', 'situacao_animal', 'ponto_referencia', 'created_at')
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude');
            },
        ])->where('id_classe', $id)->first();

        if (! $classe) {
            return response()->json(['message' => 'Classe não encontrada'], 404);
        }

        return response()->json($classe);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_cientifico' => ['required', 'string', 'max:255', 'unique:classes,nome_cientifico'],
            'nome_popular' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('foto')) {
            $dados['foto'] = $this->cloudinary->upload($request->file('foto'), 'classes');
        }

        $classe = Classe::create($dados);

        return response()->json($classe->loadCount('especies'), 201);
    }

    public function update(Request $request, int $id)
    {
        $classe = Classe::where('id_classe', $id)->first();

        if (! $classe) {
            return response()->json(['message' => 'Classe não encontrada'], 404);
        }

        $dados = $request->validate([
            'nome_cientifico' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'nome_cientifico')->ignore($id, 'id_classe'),
            ],
            'nome_popular' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('foto')) {
            $this->cloudinary->deleteByUrl($classe->getRawOriginal('foto'));
            $dados['foto'] = $this->cloudinary->upload($request->file('foto'), 'classes');
        }

        $classe->update($dados);

        return response()->json($classe->fresh()->loadCount('especies'));
    }

    public function destroy(int $id)
    {
        $classe = Classe::where('id_classe', $id)->first();

        if (! $classe) {
            return response()->json(['message' => 'Classe não encontrada'], 404);
        }

        $this->cloudinary->deleteByUrl($classe->getRawOriginal('foto'));
        $classe->delete();

        return response()->json(['message' => 'Classe excluída com sucesso']);
    }
}