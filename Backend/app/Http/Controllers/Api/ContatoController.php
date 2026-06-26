<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContatoRecebido;

class ContatoController extends Controller
{
    public function enviarContato(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensagem' => 'required|string',
        ]);

        $dados = $request->only(['nome', 'email', 'mensagem']);

        try {
            Mail::to('convivalabev@gmail.com')->send(new ContatoRecebido($dados));

            return response()->json([
                'success' => true,
                'message' => 'Mensagem enviada com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar o email: ' . $e->getMessage()
            ], 500);
        }
    }
}
