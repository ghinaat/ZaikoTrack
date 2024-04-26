<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pemakaian;
use App\Models\DetailPemakaian;
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
    $tglawal = $request->input('tglawal');
    $tglakhir = $request->input('tglakhir');
    if ($tglawal && $tglakhir) {
        $peminjamanQuery->whereBetween('tgl_pinjam', [$tglawal, $tglakhir]);
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
        $userName = Auth::user()->name;

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

            $nama_barang = Barang::where('id_barang', $id_barang)->value('nama_barang');


        // Memuat view PDF dan mengirimkan data yang diperlukan
        $pdf = PDF::loadView('laporan.pdf', [
            'peminjamans' => $peminjamans,
            'dataDetail' => $dataDetail,
            'id_barang' => $id_barang,
            'nama_barang' => $nama_barang,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'userName' => $userName,
        ]);

        // Mengunduh file PDF
        return $pdf->download('laporan-peminjaman.pdf');
    }

    public function pemakaian(Request $request)
    {
        $id_barang = $request->input('id_barang');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $pemakaianFilter = Pemakaian::query();

        // Filter berdasarkan id_barang dan rentang tanggal jika keduanya tersedia
        if ($id_barang && $start_date && $end_date) {
            $pemakaianFilter->whereBetween('tgl_pakai', [$start_date, $end_date])
                            ->whereHas('detailpemakaian.inventaris.barang', function ($query) use ($id_barang) {
                                $query->where('id_barang', $id_barang);
                            });
        } elseif ($id_barang) {
            // Filter hanya berdasarkan id_barang jika id_barang tersedia
            $pemakaianFilter->whereHas('detailpemakaian.inventaris.barang', function ($query) use ($id_barang) {
                $query->where('id_barang', $id_barang);
            });
        } elseif ($start_date && $end_date) {
            // Filter hanya berdasarkan rentang tanggal jika rentang tanggal tersedia
            $pemakaianFilter->whereBetween('tgl_pakai', [$start_date, $end_date]);
        }

        // Menyimpan nilai id_barang ke dalam sesi
        $request->session()->put('selected_id_barang', $id_barang);

        // Mendapatkan data pemakaian yang telah difilter
        $groupedPemakaians = $pemakaianFilter->orderBy('id_pemakaian', 'desc')->get();

        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();
        $siswa = User::where('level', 'siswa')->whereNotIn('id_users', [1])->get();
        $guru = Guru::all()->except(1);
        $karyawan = Karyawan::all()->except(1);

        return view('laporan.laporan-pemakaian', [
           'groupedPemakaians' => $groupedPemakaians,
            'siswa' => $siswa,
            'guru' => $guru,
            'karyawan' => $karyawan,
            'barang' => $bahanPraktik,
        ]);
    }


    public function exportPDFPemakaian(Request $request)
    {
        $defaultStartDate = '2023-01-01';
        $defaultEndDate = '2023-12-31';
        $userName = Auth::user()->name;

    
        $start_date = $request->input('start_date', $defaultStartDate);
        $end_date = $request->input('end_date', $defaultEndDate);
        $id_barang = $request->input('id_barang');
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                ->whereBetween('tgl_pakai', [$start_date, $end_date])
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pakai');
    
            // Filter berdasarkan id_barang jika id_barang tersedia
            if ($id_barang) {
                $pemakaians->whereHas('detailpemakaian.inventaris.barang', function ($query) use ($id_barang) {
                    $query->where('id_barang', $id_barang);
                });
            }
    
            $pemakaians = $pemakaians->get();
        } elseif ($id_barang) {
            // Filter hanya berdasarkan id_barang jika id_barang tersedia
            $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                ->whereHas('detailpemakaian.inventaris.barang', function ($query) use ($id_barang) {
                    $query->where('id_barang', $id_barang);
                })
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pakai')
                ->get();
        } else {
            $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pakai')
                ->get();
        }
    
        $dataDetail = DetailPemakaian::with(['inventaris.barang'])
            ->whereIn('id_pemakaian', $pemakaians->pluck('id_pemakaian'))
            ->get();
        
        
            $nama_barang = Barang::where('id_barang', $id_barang)->value('nama_barang');


        // Memuat view PDF dan mengirimkan data yang diperlukan
        $pdf = PDF::loadView('laporan.pemakaian', [
            'pemakaians' => $pemakaians,
            'dataDetail' => $dataDetail,
            'id_barang' => $id_barang,
            'nama_barang' => $nama_barang,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'userName' => $userName,
        ]);

        // Mengunduh file PDF
        return $pdf->download('laporan-pemakaian.pdf');
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
