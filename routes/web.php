<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('Home');
Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');

Route::put('/user/update/{id_users}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id_users}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
Route::post('/user', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
Route::get('/user/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
Route::post('/user/change-password', [UserController::class, 'saveChangePassword'])->name('user.saveChangePassword');

Route::resource('/ruangan', App\Http\Controllers\RuanganController::class);

Route::get('/jenisbarang', [App\Http\Controllers\JenisBarangController::class, 'index'])->name('jenisbarang.index');
Route::post('/jenisbarang', [App\Http\Controllers\JenisBarangController::class, 'store'])->name('jenisbarang.store');
Route::put('/jenisbarang/{id_jenis_barang}', [App\Http\Controllers\JenisBarangController::class, 'update'])->name('jenisbarang.update');
Route::delete('/jenisbarang/{id_jenis_barang}', [App\Http\Controllers\JenisBarangController::class, 'destroy'])->name('jenisbarang.destroy');

Route::get('/barang', [App\Http\Controllers\BarangController::class, 'index'])->name('barang.index');
Route::post('/barang', [App\Http\Controllers\BarangController::class, 'store'])->name('barang.store');
Route::put('/barang/{id_barang}', [App\Http\Controllers\BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{id_barang}', [App\Http\Controllers\BarangController::class, 'destroy'])->name('barang.destroy');

Route::get('/pembelian', [App\Http\Controllers\PembelianController::class, 'index'])->name('pembelian.index');
Route::post('/pembelian', [App\Http\Controllers\PembelianController::class, 'store'])->name('pembelian.store');
Route::put('/pembelian/{id_pembelian}', [App\Http\Controllers\PembelianController::class, 'update'])->name('pembelian.update');
Route::delete('/pembelian/{id_pembelian}', [App\Http\Controllers\PembelianController::class, 'destroy'])->name('pembelian.destroy');

Route::get('/detailpembelian/{id_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'showDetail'])->name('pembelian.showDetail');
Route::post('/detailpembelian/{id_pembelian}    ', [App\Http\Controllers\DetailPembelianController::class, 'store'])->name('detailpembelian.store');
Route::put('/detailpembelian/{id_detail_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'update'])->name('detailpembelian.update');
Route::delete('/detailpembelian/{id_detail_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'destroy'])->name('detailpembelian.destroy');