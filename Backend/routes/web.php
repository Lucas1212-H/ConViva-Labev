<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorageController;

// Rota padrão
Route::get('/', function () {
    return view('welcome');
});

// Serve arquivos de storage com headers CORS corretos.
// Sem o storage:link, requisições /storage/* não encontram arquivo estático em
// public/ e caem aqui — onde o Laravel adiciona Access-Control-Allow-Origin: *.
Route::get('/storage/{path}', [StorageController::class, 'serve'])
    ->where('path', '.*');
