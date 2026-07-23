<?php

use App\Http\Controllers\Api\ContatoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\OrdemController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/perfil', [AuthController::class, 'atualizarPerfil']);
    Route::delete('/perfil', [AuthController::class, 'deletarPerfil']);

    // Gerenciamento de Usuários (Admin)
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::post('/usuarios/{id}/aprovar', [UserController::class, 'aprovarUsuario']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);

    // Posts Restritos
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Autenticação Pública
|--------------------------------------------------------------------------
*/
Route::post('/registrar', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/esqueci-senha', [AuthController::class, 'forgotPassword']);
Route::post('/redefinir-senha', [AuthController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Ocorrências
|--------------------------------------------------------------------------
*/
Route::post('/ocorrencias', [OcorrenciaController::class, 'store']);
Route::get('/ocorrencias/pendentes', [OcorrenciaController::class, 'indexPendentes']);
Route::get('/ocorrencias/em-analise', [OcorrenciaController::class, 'indexEmAnalise']);
Route::get('/ocorrencias/arquivadas', [OcorrenciaController::class, 'indexArquivadas']);
Route::get('/ocorrencias/publicados', [OcorrenciaController::class, 'indexPublicados']);
Route::get('/ocorrencias/recentes', [OcorrenciaController::class, 'indexRecentes']);
Route::get('/ocorrencias/{id}', [OcorrenciaController::class, 'showPublicada']);
Route::put('/ocorrencias/{id}/alocar', [OcorrenciaController::class, 'alocar']);
Route::put('/ocorrencias/{id}/arquivar', [OcorrenciaController::class, 'arquivar']);
Route::put('/ocorrencias/{id}/avaliar', [OcorrenciaController::class, 'avaliar']);
Route::put('/ocorrencias/{id}/publicar', [OcorrenciaController::class, 'publicar']);
Route::put('/ocorrencias/{id}/despublicar', [OcorrenciaController::class, 'despublicar']);
Route::get('/analise/ocorrencias', [OcorrenciaController::class, 'dadosParaAnalise']);

/*
|--------------------------------------------------------------------------
| Classes e Espécies
|--------------------------------------------------------------------------
*/
Route::apiResource('classes', ClasseController::class);
Route::apiResource('ordens', OrdemController::class);
Route::apiResource('familias', FamiliaController::class);
Route::apiResource('especies', EspecieController::class);
Route::post('/especies/{id}/vincular-ocorrencias', [EspecieController::class, 'vincularOcorrencias']);

/*
|--------------------------------------------------------------------------
| Outras Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/posts', [PostController::class, 'index']);
Route::post('/contato', [ContatoController::class, 'enviarContato']);

Route::get('/imagens/{filename}', function ($filename) {
    $path = storage_path('app/public/imagens/' . $filename);

    if (! file_exists($path)) {
        return response()->json(['error' => 'Imagem não encontrada.'], 404);
    }

    $file = Storage::get($path);
    $type = Storage::mimeType($path);

    return Response::make($file, 200, [
        'Content-Type' => $type,
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET',
    ]);
});

/*
|--------------------------------------------------------------------------
| Utilitários de Servidor
|--------------------------------------------------------------------------
*/
Route::get('/gerar-link-storage', function () {
    try {
        Artisan::call('storage:link');

        return response()->json(['mensagem' => 'Link criado com sucesso!']);
    } catch (\Exception $e) {
        return response()->json(['erro' => $e->getMessage()], 500);
    }
});

Route::get('/configurar-producao', function () {
    try {
        $linkPublico = public_path('storage');
        if (File::exists($linkPublico) || is_link($linkPublico)) {
            File::delete($linkPublico);
        }

        Artisan::call('storage:link');

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Link do Storage criado com sucesso no ambiente de produção!',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'sucesso' => false,
            'erro' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/importar-csv', function () {
    try {
        Artisan::call('ocorrencias:import');
        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Importação finalizada com sucesso!',
            'logs' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'sucesso' => false,
            'erro' => $e->getMessage(),
        ], 500);
    }
});