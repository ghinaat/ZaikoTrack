<?php


use App\Http\Controllers\PemakaianController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DetailPeminjamanController;

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

Route::group(['middleware' => ['auth']], function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('Home');
Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
});

Route::group(['middleware' => ['auth']], function () {
Route::put('/user/update/{id_users}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id_users}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
Route::post('/user', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
Route::get('/user/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
Route::post('/user/change-password', [UserController::class, 'saveChangePassword'])->name('user.saveChangePassword');
});

Route::group(['middleware' => ['auth']], function () {
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

Route::get('/inventaris', [App\Http\Controllers\InventarisController::class, 'index'])->name('inventaris.index');
Route::post('/inventaris', [App\Http\Controllers\InventarisController::class, 'store'])->name('inventaris.store');
Route::put('/inventaris/{id_inventaris}', [App\Http\Controllers\InventarisController::class, 'update'])->name('inventaris.update');
Route::delete('/inventaris/{id_inventaris}', [App\Http\Controllers\InventarisController::class, 'destroy'])->name('inventaris.destroy');
Route::get('/inventaris/{id_ruangan}', [App\Http\Controllers\InventarisController::class, 'showDetail'])->name('inventaris.showDetail');
Route::delete('/inventaris/ruangan/{id_ruangan}', [App\Http\Controllers\InventarisController::class, 'destroyRuangan'])->name('inventaris.destroyRuangan');
    

Route::get('/detailpembelian/{id_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'showDetail'])->name('pembelian.showDetail');
Route::post('/detailpembelian/{id_pembelian}    ', [App\Http\Controllers\DetailPembelianController::class, 'store'])->name('detailpembelian.store');
Route::put('/detailpembelian/{id_detail_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'update'])->name('detailpembelian.update');
Route::delete('/detailpembelian/{id_detail_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'destroy'])->name('detailpembelian.destroy');

Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
Route::get('/peminjaman', [App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::get('/peminjaman/{id_peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'showDetail'])->name('peminjaman.showDetail');
Route::post('/peminjaman/create', [App\Http\Controllers\PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::put('/peminjaman/{id_peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'update'])->name('peminjaman.update');
Route::delete('/peminjaman/{id_peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
Route::post('/peminjaman/clear-cart', [PeminjamanController::class, 'clearCart'])->name('peminjaman.clearCart');
Route::get('/fetch-id-barang/{id_barang}', [App\Http\Controllers\PeminjamanController::class, 'fetchIdBarang'])->name('peminjaman.fetchIdBarang');
Route::get('/fetch-kondisi-barang/{id_ruangan}/{id_barang}', [App\Http\Controllers\PeminjamanController::class, 'fetchKondisiBarang'])->name('peminjaman.fetchKondisiBarang');
Route::post('/peminjaman/cart', [App\Http\Controllers\PeminjamanController::class, 'cart'])->name('peminjaman.cart');
Route::delete('/peminjaman/cart/{id_cart}', [App\Http\Controllers\PeminjamanController::class, 'destroyCart'])->name('peminjaman.destroyCart');


Route::delete('/peminjaman/detailPeminjaman/{id_detail_peminjaman}', [DetailPeminjamanController::class, 'destroy'])->name('detailPeminjaman.destroy');
Route::post('/peminjaman/detailPeminjaman', [App\Http\Controllers\DetailPeminjamanController::class, 'store'])->name('detailPeminjaman.store');
Route::put('/peminjaman/detailPeminjaman/{id_detail_peminjaman}', [App\Http\Controllers\DetailPeminjamanController::class, 'update'])->name('detailPeminjaman.update');


Route::get('/pemakaian', [App\Http\Controllers\PemakaianController::class, 'index'])->name('pemakaian.index');
Route::post('/pemakaian', [App\Http\Controllers\PemakaianController::class, 'store'])->name('pemakaian.store');
Route::get('/pemakaian/create', [App\Http\Controllers\PemakaianController::class, 'create'])->name('pemakaian.create');
Route::get('/get-pemakaian-data/{id_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'getPemakaianData'])->name('pemakaian.getPemakaianData');
Route::put('/pemakaian/update', [App\Http\Controllers\PemakaianController::class, 'update'])->name('pemakaian.update');
Route::delete('/pemakaian/{id_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'destroy'])->name('pemakaian.destroy');

Route::get('/detailpemakaian/{id_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'showDetail'])->name('pemakaian.showDetail');
Route::post('/pemakaian/detail', [App\Http\Controllers\PemakaianController::class, 'storeDetail'])->name('pemakaian.storeDetail');
Route::delete('/pemakaian/delete/{id_detail_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'destroyDetail'])->name('pemakaian.destroyDetail');
Route::get('/get-ruangan-options/{id_barang}', [App\Http\Controllers\PemakaianController::class, 'getRuanganOptions'])->name('pemakaian.getRuanganOptions');
Route::get('/get-siswa-options', [App\Http\Controllers\PemakaianController::class, 'getSiswaOptions'])->name('pemakaian.getSiswaOptions');
Route::get('/pemakaian/export', [App\Http\Controllers\PemakaianController::class, 'export'])->name('pemakaian.export');

Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/{id_cart}', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [App\Http\Controllers\CartController::class, 'ButtonBatal'])->name('cart.batal');


Route::resource('/siswa', App\Http\Controllers\SiswaController::class);
Route::post('/siswa/import', [ App\Http\Controllers\SiswaController::class, 'import'])->name('siswa.import');
Route::resource('/guru', App\Http\Controllers\GuruController::class);
Route::post('/guru/import', [App\Http\Controllers\GuruController::class, 'import'])->name('guru.import');
Route::post('/karyawan/import', [ App\Http\Controllers\KaryawanController::class, 'import'])->name('karyawan.import');
Route::resource('/karyawan', App\Http\Controllers\KaryawanController::class);
});

//Clear route cache:
Route::get('/route-cache', function() {
	Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});

//Clear config cache:
Route::get('/config-cache', function() {
 	Artisan::call('config:cache');
 	return 'Config cache has been cleared';
}); 
    
// Clear view cache:
Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return 'View cache has been cleared';
});