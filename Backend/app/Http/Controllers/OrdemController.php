<?php

namespace App\Http\Controllers;

use App\Models\Ordem;
use Illuminate\Http\Request;

class OrdemController extends Controller
{
    public function index(Request $request)
    {
        $query = Ordem::with(['classe:id_classe,nome_popular,nome_cientifico'])
            ->orderBy('nome_popular');

        if ($request->filled('id_classe')) {
            $query->where('id_classe', $request->integer('id_classe'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_cientifico' => ['required', 'string', 'max:255', 'unique:ordens,nome_cientifico'],
            'nome_popular' => ['required', 'string', 'max:255'],
            'id_classe' => ['required', 'integer', 'exists:classes,id_classe'],
        ]);

        $ordem = Ordem::create($dados);

        return response()->json($ordem->load('classe'), 201);
    }

    public function show(int $id)
    {
        $ordem = Ordem::with(['classe:id_classe,nome_popular,nome_cientifico'])
            ->where('id_ordem', $id)
            ->first();

        if (! $ordem) {
            return response()->json(['message' => 'Ordem não encontrada'], 404);
        }

        return response()->json($ordem);
    }

    public function update(Request $request, int $id)
    {
        $ordem = Ordem::where('id_ordem', $id)->first();

        if (! $ordem) {
            return response()->json(['message' => 'Ordem não encontrada'], 404);
        }

        $dados = $request->validate([
            'nome_cientifico' => ['required', 'string', 'max:255'],
            'nome_popular' => ['required', 'string', 'max:255'],
            'id_classe' => ['required', 'integer', 'exists:classes,id_classe'],
        ]);

        $ordem->update($dados);

        return response()->json($ordem->fresh()->load('classe'));
    }

    public function destroy(int $id)
    {
        $ordem = Ordem::where('id_ordem', $id)->first();

        if (! $ordem) {
            return response()->json(['message' => 'Ordem não encontrada'], 404);
        }

        $ordem->delete();

        return response()->json(['message' => 'Ordem excluída com sucesso']);
    }
}
