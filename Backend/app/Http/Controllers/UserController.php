<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * 📋 Listar todos os usuários ativos (Read)
     */
    public function index()
    {
        return response()->json(User::orderBy('name', 'asc')->get(), 200);
    }

    /**
     *  Atualizar dados do usuário (Update administrativo)
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'tipo_conta' => ['required', 'string', 'in:Colaborador,Administrador'],
            'password' => ['nullable', 'string', 'min:6']
        ]);

        if (!empty($dados['password'])) {
            $dados['password'] = Hash::make($dados['password']);
        } else {
            unset($dados['password']);
        }

        $user->update($dados);

        return response()->json(['message' => 'Usuário atualizado com sucesso!', 'user' => $user], 200);
    }

    /**
     *  Excluir usuário (Delete administrativo)
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'Você não pode excluir sua própria conta!'], 400);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Usuário excluído com sucesso!'], 200);
    }

    /**
     * 🔓 Aprovar o cadastro de um colaborador pendente
     */
    public function aprovarUsuario(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->status !== 'Pendente') {
            return response()->json(['message' => 'Este usuário já foi analisado.'], 400);
        }

        $user->update(['status' => 'Ativo']);

        return response()->json([
            'message' => "O cadastro de {$user->name} foi aprovado com sucesso!"
        ], 200);
    }
}