<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(
            Categoria::withCount('especies')
                ->orderBy('nome_popular')
                ->get()
        );
    }

    public function show(int $id)
    {
        $categoria = Categoria::with('especies')->where('id_categoria', $id)->first();

        if (! $categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        return response()->json($categoria);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_cientifico' => ['required', 'string', 'max:255', 'unique:categorias,nome_cientifico'],
            'nome_popular' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:5120'],
        ]);

        // Se houver foto, fazer upload
        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('categorias', 'public');
            $dados['foto'] = $caminhoFoto;
        }

        $categoria = Categoria::create($dados);

        return response()->json($categoria->loadCount('especies'), 201);
    }

    public function update(Request $request, int $id)
    {
        // Busca usando a sua chave personalizada do banco
        $categoria = Categoria::where('id_categoria', $id)->first();

        if (! $categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        $dados = $request->validate([
            'nome_cientifico' => [
                'required',
                'string',
                'max:255',
                // Corrigido e validado para ignorar o ID correto usando sua coluna customizada
                Rule::unique('categorias', 'nome_cientifico')->ignore($id, 'id_categoria'),
            ],
            'nome_popular' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:5120'], // Limite de 5MB seguro
        ]);

        // Se houver nova foto, faz o gerenciamento do upload
        if ($request->hasFile('foto')) {
            // Deleta a imagem antiga do disco local para não acumular lixo digital
            if ($categoria->foto && Storage::disk('public')->exists($categoria->foto)) {
                Storage::disk('public')->delete($categoria->foto);
            }
            
            $caminhoFoto = $request->file('foto')->store('categorias', 'public');
            $dados['foto'] = $caminhoFoto;
        }

        // Executa a atualização dos campos validados no banco de dados
        $categoria->update($dados);

        // Retorna a categoria atualizada com o contador de espécies atualizado para o Vue.js
        return response()->json($categoria->fresh()->loadCount('especies'));
    }

    public function destroy(int $id)
    {
        $categoria = Categoria::where('id_categoria', $id)->first();

        if (! $categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria excluída com sucesso']);
    }
}
