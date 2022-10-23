<?php

use App\Http\Controllers\Api\V1\FicheroController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['throttle:tresconsultasminuto'])->group(function () {
    Route::get('ficheros', [FicheroController::class, 'listarFicheros']);
    Route::post('ficheros', [FicheroController::class, 'guardarFichero']);
    Route::delete('ficheros/{idFichero}', [FicheroController::class, 'eliminarFichero']);
    Route::delete('ficheros/delete-fisico/{idFichero}', [FicheroController::class, 'eliminarFicheroFisico']);

    Route::get('ficheros/descargar/{idFichero}', [FicheroController::class, 'descargarFichero']);
});
