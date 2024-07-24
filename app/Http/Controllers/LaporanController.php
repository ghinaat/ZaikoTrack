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
    public function peminjaman(Request $request)
{
    $user = Auth::user();
    $peminjamanQuery = Peminjaman::query();

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

    // Ambil nama peminjam yang dipilih dari permintaan
    $selectedPeminjam = $request->input('nama_peminjam');

    if ($selectedPeminjam) {
        $peminjamanQuery->where(function ($query) use ($selectedPeminjam) {
            $query->whereHas('users', function ($query) use ($selectedPeminjam) {
                $query->where('name', $selectedPeminjam);
            })->orWhereHas('guru', function ($query) use ($selectedPeminjam) {
                $query->where('nama_guru', $selectedPeminjam);
            })->orWhereHas('karyawan', function ($query) use ($selectedPeminjam) {
                $query->where('nama_karyawan', $selectedPeminjam);
            });
        });
    }

    // Ambil semua data peminjaman dengan waktu peminjamannya
    $peminjamans = Peminjaman::latest()->get();

    // Mengumpulkan semua nama peminjam dari peminjaman yang unik
    $peminjam_names = $peminjamans->flatMap(function ($peminjaman) {
        return [
            $peminjaman->users->name,
            $peminjaman->guru->nama_guru,
            $peminjaman->karyawan->nama_karyawan,
        ];
    })->unique();

    // Simpan semua nama peminjam dalam sesi
    session()->put('all_peminjam_names', $peminjam_names->unique()->values()->all());

    // Handle selected nama peminjam
    $selectedNamaPeminjam = $request->input('nama_peminjam');
    session()->put('selected_nama_peminjam', $selectedNamaPeminjam);

    // Retrieve the filtered Peminjaman records
    $peminjaman = $peminjamanQuery->orderBy('id_peminjaman', 'desc')->get();
    session()->put('selected_id_barang', $id_barang);

    // Retrieve other necessary data
    $barang = Barang::where('id_jenis_barang', '!=', 3)->get();
    $detailPeminjaman = DetailPeminjaman::all();
    $users = User::where('id_users', '!=', 1)->get();
    $guru = Guru::where('id_guru', '!=', 1)->get();
    $karyawan = Karyawan::where('id_karyawan', '!=', 1)->get(); 

    return view('laporan.laporan-peminjaman', [
        'peminjaman' => $peminjaman,
        'peminjam_names' => $peminjam_names,
        'barang' => $barang,
        'id_barang' => $id_barang,
        'detailPeminjaman' => $detailPeminjaman,
        'users' => $users,
        'guru' => $guru,
        'karyawan' => $karyawan,
    ]);
}

public function exportPDFPeminjaman(Request $request)
    {
        $tglawal = $request->input('tglawal');
        $tglakhir = $request->input('tglakhir');
        $id_barang = $request->input('id_barang');
        $userName = Auth::user()->name;

        // Ambil data peminjaman berdasarkan filter yang diberikan
    $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan', 'detailPeminjaman.inventaris.barang']);

     // Filter berdasarkan id user jika id user dipilih
     $id_user = $request->input('id_user');
     if ($id_user) {
         $peminjamans->where('id_users', $id_user);
     }
 
     // Filter berdasarkan id guru jika id guru dipilih
     $id_guru = $request->input('id_guru');
     if ($id_guru) {
         $peminjamans->where('id_guru', $id_guru);
     }
 
     // Filter berdasarkan id karyawan jika id karyawan dipilih
     $id_karyawan = $request->input('id_karyawan');
     if ($id_karyawan) {
         $peminjamans->where('id_karyawan', $id_karyawan);
     }
 

        // Ambil data peminjaman berdasarkan filter yang diberikan
        if ($id_barang == '0') {
            // Jika jenis barang adalah "all", ambil semua data peminjaman
            if ($tglawal && $tglakhir) {
                // Jika tanggal awal dan tanggal akhir diinputkan, ambil data sesuai rentang tanggal
                $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                    ->whereBetween('tgl_pinjam', [$tglawal, $tglakhir])
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pinjam')
                    ->get();
            } else {
                // Jika tanggal awal dan tanggal akhir tidak diinputkan, ambil semua data peminjaman
                $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pinjam')
                    ->get();
            }
        } else {
            // Jika jenis barang tertentu dipilih, ambil data peminjaman sesuai dengan jenis barang
            if ($tglawal && $tglakhir) {
                // Jika tanggal awal dan tanggal akhir diinputkan, ambil data sesuai rentang tanggal
                $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                    ->whereHas('detailPeminjaman.inventaris.barang', function ($query) use ($id_barang) {
                        $query->where('id_barang', $id_barang);
                    })
                    ->whereBetween('tgl_pinjam', [$tglawal, $tglakhir])
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pinjam')
                    ->get();
            } else {
                // Jika tanggal awal dan tanggal akhir tidak diinputkan, ambil data sesuai dengan jenis barang
                $peminjamans = Peminjaman::with(['users', 'guru', 'karyawan'])
                    ->whereHas('detailPeminjaman.inventaris.barang', function ($query) use ($id_barang) {
                        $query->where('id_barang', $id_barang);
                    })
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pinjam')
                    ->get();
            }
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

        // Set ukuran kertas dan orientasi
        $pdf->setPaper('A4', 'potret');

        // Unduh PDF langsung
        return $pdf->download('Laporan Peminjaman Barang.pdf');
    }

    public function pemakaian(Request $request)
    {
        $id_barang = $request->input('id_barang');
        $start_date = $request->input('$start_date');
        $end_date = $request->input('$end_date');

        $pemakaianFilter = Pemakaian::query();

        // Filter by date range
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if ($start_date && $end_date) {
            $pemakaianFilter->whereBetween('tgl_pakai', [$start_date, $end_date]);
        }

        // Filter by selected id_barang
        $id_barang = $request->input('id_barang');
        if ($id_barang) {
            $pemakaianFilter->whereHas('detailPemakaian.inventaris.barang', function ($query) use ($id_barang) {
                $query->where('id_barang', $id_barang);
            });
        }

        // Ambil nama peminjam yang dipilih dari permintaan
        $selectedPemakaian = $request->input('nama_peminjam');

        if ($selectedPemakaian) {
            $pemakaianFilter->where(function ($query) use ($selectedPemakaian) {
                $query->whereHas('users', function ($query) use ($selectedPemakaian) {
                    $query->where('name', $selectedPemakaian);
                })->orWhereHas('guru', function ($query) use ($selectedPemakaian) {
                    $query->where('nama_guru', $selectedPemakaian);
                })->orWhereHas('karyawan', function ($query) use ($selectedPemakaian) {
                    $query->where('nama_karyawan', $selectedPemakaian);
                });
            });
        }

        // Ambil semua data peminjaman dengan waktu peminjamannya
        $groupedPemakaians = Pemakaian::latest()->get();

        // Mengumpulkan semua nama peminjam dari peminjaman yang unik
        $peminjam_names = $groupedPemakaians->flatMap(function ($pemakaian) {
            return [
                $pemakaian->users->name,
                $pemakaian->guru->nama_guru,
                $pemakaian->karyawan->nama_karyawan,
            ];
        })->unique();

        // Simpan semua nama peminjam dalam sesi
        session()->put('all_peminjam_names', $peminjam_names->unique()->values()->all());

        // Handle selected nama peminjam
        $selectedNamaPemakaian = $request->input('nama_peminjam');
        session()->put('selected_nama_peminjam', $selectedNamaPemakaian);

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
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $id_barang = $request->input('id_barang');
        $userName = Auth::user()->name;
        

        // Ambil data pemakaian berdasarkan filter yang diberikan
        $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan', 'detailPemakaian.inventaris.barang']);

        // Filter berdasarkan id user jika id user dipilih
        $id_user = $request->input('id_user');
        if ($id_user) {
            $pemakaians->where('id_users', $id_user);
        }

        // Filter berdasarkan id guru jika id guru dipilih
        $id_guru = $request->input('id_guru');
        if ($id_guru) {
            $pemakaians->where('id_guru', $id_guru);
        }

        // Filter berdasarkan id karyawan jika id karyawan dipilih
        $id_karyawan = $request->input('id_karyawan');
        if ($id_karyawan) {
            $pemakaians->where('id_karyawan', $id_karyawan);
        }

        // Ambil data peminjaman berdasarkan filter yang diberikan
        if ($id_barang == '0') {
            // Jika jenis barang adalah "all", ambil semua data peminjaman
            if ($start_date && $end_date) {
                // Jika tanggal awal dan tanggal akhir diinputkan, ambil data sesuai rentang tanggal
                $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                    ->whereBetween('tgl_pakai', [$start_date, $end_date])
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pakai')
                    ->get();
            } else {
                // Jika tanggal awal dan tanggal akhir tidak diinputkan, ambil semua data Pemakaian
                $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pakai')
                    ->get();
            }
        } else {
            // Jika jenis barang tertentu dipilih, ambil data Pemakaian sesuai dengan jenis barang
            if ($start_date && $end_date) {
                // Jika tanggal awal dan tanggal akhir diinputkan, ambil data sesuai rentang tanggal
                $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                    ->whereHas('detailPemakaian.inventaris.barang', function ($query) use ($id_barang) {
                        $query->where('id_barang', $id_barang);
                    })
                    ->whereBetween('tgl_pakai', [$start_date, $end_date])
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pakai')
                    ->get();
            } else {
                // Jika tanggal awal dan tanggal akhir tidak diinputkan, ambil data sesuai dengan jenis barang
                $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                    ->whereHas('detailPemakaian.inventaris.barang', function ($query) use ($id_barang) {
                        $query->where('id_barang', $id_barang);
                    })
                    ->orderBy('id_users')
                    ->orderBy('id_guru')
                    ->orderBy('id_karyawan')
                    ->orderBy('tgl_pakai')
                    ->get();
            }
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

        // Set ukuran kertas dan orientasi
        $pdf->setPaper('A4', 'potret');

        // Mengunduh file PDF
        return $pdf->download('Laporan Pemakaian Barang.pdf');
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
