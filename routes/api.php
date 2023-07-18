<?php

use App\Http\Controllers\LoteController;
use App\Http\Controllers\PaqueteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function ()
{
    Route::post('/paquetes',[PaqueteController::class,"CrearPaquete"]);
    Route::get('/paquetes',[PaqueteController::class,"ListarPaquetes"]);

    Route::post('/lotes',[LoteController::class,"CrearLote"]);
    Route::get('/lotes',[LoteController::class,"ListarLotes"]);

    Route::post('/lotes/asignar',[LoteController::class,"AsignarPaquete"]);

});
