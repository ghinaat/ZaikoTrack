<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Guru;
use App\Models\Karyawan;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $user = Auth::user();
    $peminjamanQuery = Peminjaman::query();

    // Filter by user level
    if ($user->level == 'siswa') {
        $peminjamanQuery->where('id_users', auth()->user()->id_users);
    }

    // Filter by date range
    $tanggal_awal = $request->input('tanggal_awal');
    $tanggal_akhir = $request->input('tanggal_akhir');
    if ($tanggal_awal && $tanggal_akhir) {
        $peminjamanQuery->whereBetween('tgl_pinjam', [$tanggal_awal, $tanggal_akhir]);
    }

    // Filter by selected id_barang
    $id_barang = $request->input('id_barang');
    if ($id_barang) {
        $peminjamanQuery->whereHas('detailPeminjaman.inventaris.barang', function ($query) use ($id_barang) {
            $query->where('id_barang', $id_barang);
        });
    }

    // Retrieve the filtered Peminjaman records
    $peminjaman = $peminjamanQuery->orderBy('id_peminjaman', 'desc')->get();
    session()->put('selected_id_barang', $id_barang);

    // Retrieve other necessary data
    $barang = Barang::where('id_jenis_barang', '!=', 3)->get();
    $detailPeminjaman = DetailPeminjaman::all();
    $users = User::where('id_users', '!=', 1)->orderByRaw("LOWER(name)")->get();
    $guru = Guru::where('id_guru', '!=', 1)->orderByRaw("LOWER(nama_guru)")->get();
    $karyawan = Karyawan::where('id_karyawan', '!=', 1)->orderByRaw("LOWER(nama_karyawan)")->get(); 

    return view('laporan.laporan-peminjaman', [
        'peminjaman' => $peminjaman,
        'barang' => $barang,
        'id_barang' => $id_barang,
        'detailPeminjaman' => $detailPeminjaman,
        'users' => $users,
        'guru' => $guru,
        'karyawan' => $karyawan,
    ]);
}

public function exportPDF(Request $request)
    {
        // Mengatur tanggal awal dan akhir default jika tidak disediakan
        $defaultStartDate = '2023-01-01';
        $defaultEndDate = '2023-12-31';

        $tglawal = $request->input('tglawal', $defaultStartDate);
        $tglakhir = $request->input('tglakhir', $defaultEndDate);
        $id_barang = $request->input('id_barang');

        // Mengambil data peminjaman sesuai filter yang diberikan
        if ($request->filled('tglawal') && $request->filled('tglakhir')) {
            $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                ->whereBetween('tgl_pinjam', [$tglawal, $tglakhir])
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pinjam')
                ->get();
        } elseif ($request->filled('id_barang')) {
            $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                ->whereHas('detailPeminjaman.inventaris.barang', function ($query) use ($id_barang) {
                    $query->where('id_barang', $id_barang);
                })
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pinjam')
                ->get();
        } elseif ($request->filled('tglawal') && $request->filled('tglakhir') && $request->filled('id_barang')) {
            $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                ->whereHas('detailPeminjaman.inventaris.barang', function ($query) use ($id_barang) {
                    $query->where('id_barang', $id_barang);
                })
                ->whereBetween('tgl', [$tglawal, $tglakhir])
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pinjam')
                ->get();
        } else {
            $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pinjam')
                ->get();
        }

        // Mengambil data detail peminjaman
        $dataDetail = DetailPeminjaman::with(['inventaris.barang'])
            ->whereIn('id_peminjaman', $peminjamans->pluck('id_peminjaman'))
            ->get();


        // Memuat view PDF dan mengirimkan data yang diperlukan
        $pdf = PDF::loadView('laporan.pdf', [
            'peminjamans' => $peminjamans,
            'dataDetail' => $dataDetail,
            'id_barang' => $id_barang,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
        ]);

        // Mengunduh file PDF
        return $pdf->download('laporan-peminjaman.pdf');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
