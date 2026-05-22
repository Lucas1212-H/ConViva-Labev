<?php

use Illuminate\Support\Facades\Route;

// Rotas web
Route::get('/', function () {
    return view('welcome');
});
