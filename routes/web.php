<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SociosController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\MunicipiosController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\QualificacoesController;
use App\Http\Controllers\EstabelecimentosController;
use App\Http\Controllers\NaturezasController;
use App\Http\Controllers\MotivosController;
use App\Http\Controllers\PaisesController;
use App\Http\Controllers\CnaesController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);

// Method 1: Full resource routes (recommended)
Route::resource('socios', SociosController::class);
Route::resource('empresas', EmpresasController::class);
Route::resource('municipios', MunicipiosController::class);
Route::resource('estados', EstadosController::class);
Route::resource('qualificacoes', QualificacoesController::class);
Route::resource('estabelecimentos', EstabelecimentosController::class)->only(['index']);
Route::resource('naturezas', NaturezasController::class);
Route::resource('motivos', MotivosController::class);
Route::resource('paises', PaisesController::class);
Route::resource('cnaes', CnaesController::class);