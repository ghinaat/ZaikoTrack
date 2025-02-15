<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\API\{
    BarangApiController,
    JenisBarangApiController,
    InventarisApiController
};

Route::get('/inventaris', [InventarisApiController::class, 'index']);
Route::put('/inventaris/{id}/kondisi', [InventarisApiController::class, 'updateKondisi']);
Route::get('/barang', [BarangApiController::class, 'index']);
Route::get('/jenis-barang', [JenisBarangApiController::class, 'index']);

