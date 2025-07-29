<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\EstabelecimentoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Method 1: Full resource routes (recommended)
Route::apiResource('socios', EstabelecimentoController::class);
Route::apiResource('empresas', EstabelecimentoController::class);
Route::apiResource('municipios', EstabelecimentoController::class);
Route::apiResource('estados', EstabelecimentoController::class);
Route::apiResource('qualificacoes', EstabelecimentoController::class);
Route::apiResource('naturezas', EstabelecimentoController::class);
Route::apiResource('estabelecimentos', EstabelecimentoController::class);
Route::apiResource('estabelecimentos', EstabelecimentoController::class);
Route::apiResource('estabelecimentos', EstabelecimentoController::class);
Route::apiResource('estabelecimentos', EstabelecimentoController::class);
Route::apiResource('estabelecimentos', EstabelecimentoController::class);
