<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use Illuminate\Http\Request;

class FamiliaController extends Controller
{
    public function index(Request $request)
    {
        $query = Familia::with(['ordem:id_ordem,nome_popular,nome_cientifico'])
            ->orderBy('nome_popular');

        if ($request->filled('id_ordem')) {
            $query->where('id_ordem', $request->integer('id_ordem'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_cientifico' => ['required', 'string', 'max:255', 'unique:familias,nome_cientifico'],
            'nome_popular' => ['required', 'string', 'max:255'],
            'id_ordem' => ['required', 'integer', 'exists:ordens,id_ordem'],
        ]);

        $familia = Familia::create($dados);

        return response()->json($familia->load('ordem'), 201);
    }

    public function show(int $id)
    {
        $familia = Familia::with(['ordem:id_ordem,nome_popular,nome_cientifico'])
            ->where('id_familia', $id)
            ->first();

        if (! $familia) {
            return response()->json(['message' => 'Família não encontrada'], 404);
        }

        return response()->json($familia);
    }

    public function update(Request $request, int $id)
    {
        $familia = Familia::where('id_familia', $id)->first();

        if (! $familia) {
            return response()->json(['message' => 'Família não encontrada'], 404);
        }

        $dados = $request->validate([
            'nome_cientifico' => ['required', 'string', 'max:255'],
            'nome_popular' => ['required', 'string', 'max:255'],
            'id_ordem' => ['required', 'integer', 'exists:ordens,id_ordem'],
        ]);

        $familia->update($dados);

        return response()->json($familia->fresh()->load('ordem'));
    }

    public function destroy(int $id)
    {
        $familia = Familia::where('id_familia', $id)->first();

        if (! $familia) {
            return response()->json(['message' => 'Família não encontrada'], 404);
        }

        $familia->delete();

        return response()->json(['message' => 'Família excluída com sucesso']);
    }
}
