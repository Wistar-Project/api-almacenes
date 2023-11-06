<?php

use App\Http\Controllers\DestinoController;
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

Route::prefix('v1')->group(function () {
    Route::post('/paquetes', [PaqueteController::class, "CrearPaquete"])->middleware("auth:api");
    Route::get('/paquetes', [PaqueteController::class, "ListarPaquetes"])->middleware("auth:api");
    Route::get('/paquetes/{id}', [PaqueteController::class, 'verInformacionDeUnPaquete'])-> middleware("auth:api");
    Route::post('/lotes', [LoteController::class, "CrearLote"])->middleware("auth:api");
    Route::get('/lotes', [LoteController::class, "ListarLotes"])->middleware("auth:api");
    Route::get('/lotes/{d}', [LoteController::class, "MostrarLote"])->middleware("auth:api");
    Route::get('/destinos', [ DestinoController::class, "MostrarDestinos" ])->middleware("auth:api");
    Route::get('/lotes/asignar/{d}', [ LoteController::class, "MostrarLotesParaAsignar" ]);
    Route::get('/paquetes/asignar/{d}', [ PaqueteController::class, "MostrarPaquetesParaAsignar" ]);
    Route::post('/lotes/asignar', [LoteController::class, "AsignarPaquete"])->middleware("auth:api");

});
