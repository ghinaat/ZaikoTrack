<?php


use App\Http\Controllers\PemakaianController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DetailPeminjamanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\EmailConfigurationController;
use App\Http\Controllers\LaporanController;


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
Route::post('/user/import', [ App\Http\Controllers\UserController::class, 'import'])->name('user.import');
Route::get('/user/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
Route::put('/user/change-password/{id_users}', [UserController::class, 'saveChangePassword'])->name('user.saveChangePassword');
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
Route::get('/barang/exportalat', [App\Http\Controllers\BarangController::class, 'exportAlatPerlengkapan'])->name('barang.exportAlatPerlengkapan');
Route::get('/barang/exportbahan', [App\Http\Controllers\BarangController::class, 'exportBahan'])->name('barang.exportBahan');
Route::get('/barang/print/{id_barang}', [App\Http\Controllers\BarangController::class, 'print'])->name('barang.print');

Route::get('/pembelian', [App\Http\Controllers\PembelianController::class, 'index'])->name('pembelian.index');
Route::post('/pembelian', [App\Http\Controllers\PembelianController::class, 'store'])->name('pembelian.store');
Route::put('/pembelian/{id_pembelian}', [App\Http\Controllers\PembelianController::class, 'update'])->name('pembelian.update');
Route::delete('/pembelian/{id_pembelian}', [App\Http\Controllers\PembelianController::class, 'destroy'])->name('pembelian.destroy');


Route::get('/detailpembelian/{id_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'showDetail'])->name('pembelian.showDetail');
Route::post('/detailpembelian/{id_pembelian}    ', [App\Http\Controllers\DetailPembelianController::class, 'store'])->name('detailpembelian.store');
Route::put('/detailpembelian/{id_detail_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'update'])->name('detailpembelian.update');
Route::get('fetch-id-barang/{id_detail_pembelian}',  [App\Http\Controllers\DetailPembelianController::class, 'getIdBarang'])->name('detailpembelian.fetchIdbarang');
// Route::delete('/detailpembelian/{id_detail_pembelian}', [App\Http\Controllers\DetailPembelianController::class, 'destroy'])->name('detailpembelian.destroy');

Route::get('/inventaris', [App\Http\Controllers\InventarisController::class, 'index'])->name('inventaris.index');
Route::get('/inventaris/barcode/{id_ruangan}', [App\Http\Controllers\InventarisController::class, 'barcode'])->name('inventaris.barcode');
Route::post('/inventaris', [App\Http\Controllers\InventarisController::class, 'store'])->name('inventaris.store');
Route::put('/inventaris/barcode/{id_ruangan}', [App\Http\Controllers\InventarisController::class, 'addBarcode'])->name('inventaris.addBarcode');
Route::put('/inventaris/{id_inventaris}', [App\Http\Controllers\InventarisController::class, 'update'])->name('inventaris.update');
Route::delete('/inventaris/{id_inventaris}', [App\Http\Controllers\InventarisController::class, 'destroy'])->name('inventaris.destroy');
Route::get('/inventaris/{id_ruangan}', [App\Http\Controllers\InventarisController::class, 'showDetail'])->name('inventaris.showDetail');
Route::delete('/inventaris/ruangan/{id_ruangan}', [App\Http\Controllers\InventarisController::class, 'destroyRuangan'])->name('inventaris.destroyRuangan');
Route::get('/inventaris/fetch-id-barang/{id_barang}', [App\Http\Controllers\InventarisController::class, 'fetchIdBarang'])->name('inventaris.fetchIdBarang');

Route::get('/peminjaman/export', [PeminjamanController::class, 'export'])->name('peminjaman.export');
Route::get('/peminjaman/qrcode/{id_peminjaman}', [PeminjamanController::class, 'Qrcode'])->name('peminjaman.Qrcode');
Route::get('/peminjaman/barcode', [PeminjamanController::class, 'Barcode'])->name('peminjaman.barcode');
Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
Route::get('/peminjaman', [App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::get('/peminjaman/{id_peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'showDetail'])->name('peminjaman.showDetail');
Route::post('/peminjaman/create', [App\Http\Controllers\PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::put('/peminjaman/create/{id_peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'update'])->name('peminjaman.update');
Route::delete('/peminjaman/create/{id_peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
Route::get('/fetch-id-barang/{id_barang}', [App\Http\Controllers\PeminjamanController::class, 'fetchIdBarang'])->name('peminjaman.fetchIdBarang');
Route::get('/fetch-nama-barang/{id_barang}', [App\Http\Controllers\PeminjamanController::class, 'fetchNamaBarang'])->name('peminjaman.fetchNamaBarang');
Route::get('/peminjaman/filter', [PeminjamanController::class, 'filter'])->name('peminjaman.filter');
Route::get('/fetch-peminjaman-status/{id_peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'getSiswaOptions'])->name('peminjaman.getSiswaOptions');

Route::get('/detailPeminjaman/return/{id_detail_peminjaman}', [DetailPeminjamanController::class, 'Return'])->name('detailPeminjaman.return');
Route::post('/peminjaman/detailPeminjaman', [App\Http\Controllers\DetailPeminjamanController::class, 'store'])->name('detailPeminjaman.store');
Route::delete('/peminjaman/detailPeminjaman/{id_detail_peminjaman}', [DetailPeminjamanController::class, 'destroy'])->name('detailPeminjaman.destroy');
Route::put('/peminjaman/detailPeminjaman/returnBarcode/{id_detail_peminjaman}', [App\Http\Controllers\DetailPeminjamanController::class, 'returnBarcode'])->name('detailPeminjaman.returnBarcode');
Route::put('/peminjaman/detailPeminjaman/{id_detail_peminjaman}', [App\Http\Controllers\DetailPeminjamanController::class, 'update'])->name('detailPeminjaman.update');
Route::post('/peminjaman/detailPeminjaman/barcode', [App\Http\Controllers\DetailPeminjamanController::class, 'AddBarcode'])->name('detailPeminjaman.AddBarcode');
Route::post('/peminjaman/detailPeminjaman/qrcode/{id_peminjaman}', [App\Http\Controllers\DetailPeminjamanController::class, 'AddQrcode'])->name('detailPeminjaman.AddQrcode');


Route::get('/pemakaian', [App\Http\Controllers\PemakaianController::class, 'index'])->name('pemakaian.index');
Route::post('/pemakaian', [App\Http\Controllers\PemakaianController::class, 'store'])->name('pemakaian.store');
Route::get('/pemakaian/create', [App\Http\Controllers\PemakaianController::class, 'create'])->name('pemakaian.create');
Route::get('/get-pemakaian-data/{id_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'getPemakaianData'])->name('pemakaian.getPemakaianData');
Route::put('/pemakaian/update', [App\Http\Controllers\PemakaianController::class, 'update'])->name('pemakaian.update');
Route::delete('/pemakaian/{id_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'destroy'])->name('pemakaian.destroy');


Route::get('/detailpemakaian/{id_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'showDetail'])->name('pemakaian.showDetail');
Route::post('/pemakaian/detail', [App\Http\Controllers\PemakaianController::class, 'storeDetail'])->name('pemakaian.storeDetail');
Route::put('/pemakaian/detail/{id_detail_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'updateDetail'])->name('pemakaian.updateDetail');
Route::delete('/pemakaian/delete/{id_detail_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'destroyDetail'])->name('pemakaian.destroyDetail');
Route::get('/get-ruangan-options/{id_barang}', [App\Http\Controllers\PemakaianController::class, 'getRuanganOptions'])->name('pemakaian.getRuanganOptions');
Route::get('/get-stok-options/{id_ruangan}', [App\Http\Controllers\PemakaianController::class, 'getStokOptions'])->name('pemakaian.getStokOptions');
Route::get('/get-ruangan-and-stok/{id_detail_pemakaian}', [App\Http\Controllers\PemakaianController::class, 'getRuanganAndStok'])->name('pemakaian.getRuanganStokUpdate');

Route::get('/get-siswa-options', [App\Http\Controllers\PemakaianController::class, 'getSiswaOptions'])->name('pemakaian.getSiswaOptions');
Route::get('/pemakaian/export', [App\Http\Controllers\PemakaianController::class, 'export'])->name('pemakaian.export');
Route::get('/pemakaian/filter', [App\Http\Controllers\PemakaianController::class, 'filter'])->name('pemakaian.filter');


Route::resource('/siswa', App\Http\Controllers\SiswaController::class);
Route::post('/siswa/import', [ App\Http\Controllers\SiswaController::class, 'import'])->name('siswa.import');
Route::resource('/guru', App\Http\Controllers\GuruController::class);
Route::post('/guru/import', [App\Http\Controllers\GuruController::class, 'import'])->name('guru.import');
Route::post('/karyawan/import', [ App\Http\Controllers\KaryawanController::class, 'import'])->name('karyawan.import');
Route::resource('/karyawan', App\Http\Controllers\KaryawanController::class);
});


Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
Route::get('/notifikasi/fetch', [NotifikasiController::class, 'fetch'])->name('notifikasi.fetch');
Route::get('/notifikasi/{id_notifikasi}/detail', [NotifikasiController::class, 'detail'])->name('notifikasi.detail');

Route::get('/email-configuration', [EmailConfigurationController::class, 'show'])->name('emailConfiguration.show');
Route::post('/email-configuration', [EmailConfigurationController::class, 'update'])->name('emailConfiguration.update');

Route::get('/laporan-peminjaman', [LaporanController::class, 'index'])->name('laporan-peminjaman');
Route::get('/laporan/pdf', [LaporanController::class, 'exportPDF'])->name('downloadpdf');