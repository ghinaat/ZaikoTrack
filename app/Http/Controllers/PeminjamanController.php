<?php

namespace App\Http\Controllers;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\PeminjamanExport;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamanController extends Controller
{
    
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

    return view('peminjaman.index', [
        'peminjaman' => $peminjaman,
        'barang' => $barang,
        'id_barang' => $id_barang,
        'detailPeminjaman' => $detailPeminjaman,
        'users' => $users,
        'guru' => $guru,
        'karyawan' => $karyawan,
    ]);
}


    public function create()
    {
      
        $peminjaman = Peminjaman::all();
        
        // Get the maximum id_inventaris for each id_ruangan
        $ruangan = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_ruangan')
            ->get();

          $id_barang_options = Inventaris::whereIn('id_ruangan', $ruangan->pluck('id_ruangan'))
            ->join('barang', 'inventaris.id_barang', '=', 'barang.id_barang')
            ->leftJoin('detail_peminjaman', 'inventaris.id_inventaris', '=', 'detail_peminjaman.id_inventaris')
            ->whereNotIn('barang.id_jenis_barang', [3]) // Exclude specific jenis_barang
            ->where(function($query) {
                $query->whereNull('detail_peminjaman.status') // Include cases with no status (not borrowed)
                    ->orWhere('detail_peminjaman.status', '!=', 'dipinjam'); // Exclude borrowed status
            })
            ->select('inventaris.id_barang', DB::raw('MAX(inventaris.id_inventaris) as max_id_inventaris'))
            ->groupBy('inventaris.id_barang')
            ->get();
        
        $barang =  Barang::where('id_jenis_barang', '!=', 3)->get();
    
        $users = User::where('level', 'siswa')
            ->orderByRaw("LOWER(name)")
            ->get();
        $guru = Guru::where('id_guru', '!=', 1)  
        ->orderByRaw("LOWER(nama_guru)")  
        ->get(); 
        $karyawan = Karyawan::where('id_karyawan', '!=', 1)  
        ->orderByRaw("LOWER(nama_karyawan)")  
        ->get(); 
        $peminjamans = Peminjaman::with('detailPeminjaman')->get();
        
        
        
        return view('peminjaman.create', [
            'peminjaman' => $peminjaman,
            'peminjamans' => $peminjamans,
            'users' => $users,
            'guru' => $guru,
            'karyawan' => $karyawan,
            'ruangan' => $ruangan,
            'id_barang_options' => $id_barang_options,
            'barang' => $barang,
        
            
        ]);
    }

    public function Barcode()
    {
      
        $peminjaman = Peminjaman::all();
        
        // Get the maximum id_inventaris for each id_ruangan
        $ruangan = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_ruangan')
            ->get();
    
        // Get the maximum id_inventaris for each id_barang within the selected id_ruangan
        $id_barang_options = Inventaris::whereIn('id_ruangan', $ruangan->pluck('id_ruangan'))
            ->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')
            ->get();
      
        $users = User::where('id_users', '!=', 1)  
        ->orderByRaw("LOWER(name)")  
        ->get(); 
        $guru = Guru::where('id_guru', '!=', 1)  
        ->orderByRaw("LOWER(nama_guru)")  
        ->get(); 
        $karyawan = Karyawan::where('id_karyawan', '!=', 1)  
        ->orderByRaw("LOWER(nama_karyawan)")  
        ->get(); 
        $peminjamans = Peminjaman::with('detailPeminjaman')->get();
        $idPeminjaman = session('id_peminjaman');
        
        
        return view('peminjaman.barcode', [
            'peminjaman' => $peminjaman,
            'peminjamans' => $peminjamans,
            'users' => $users,
            'guru' => $guru,
            'karyawan' => $karyawan,
            'ruangan' => $ruangan,
            'id_barang_options' => $id_barang_options,
            'idPeminjaman' => $idPeminjaman,
            
        ]);
    }

    public function Qrcode($id_peminjaman)
    {
        $id_peminjaman = Peminjaman::find($id_peminjaman);
        return view('peminjaman.add', [ 'id_peminjaman'   => $id_peminjaman]);
    }


    
    public function showDetail($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $detailPeminjamans = DetailPeminjaman::where('id_peminjaman', $id_peminjaman)->get();
        $ruangan = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
        ->groupBy('id_ruangan')
        ->get();
        $id_barang_options = Inventaris::whereIn('id_ruangan', $ruangan->pluck('id_ruangan'))
        ->join('barang', 'inventaris.id_barang', '=', 'barang.id_barang')
        ->leftJoin('detail_peminjaman', 'inventaris.id_inventaris', '=', 'detail_peminjaman.id_inventaris')
        ->whereNotIn('barang.id_jenis_barang', [3]) // Exclude specific jenis_barang
        ->where(function($query) {
            $query->whereNull('detail_peminjaman.status') // Include cases with no status (not borrowed)
                ->orWhere('detail_peminjaman.status', '!=', 'dipinjam'); // Exclude borrowed status
        })
        ->select('inventaris.id_barang', DB::raw('MAX(inventaris.id_inventaris) as max_id_inventaris'))
        ->groupBy('inventaris.id_barang')
        ->get();
        
        $ruangans = Ruangan::all();
      
    
        return view('peminjaman.show', [
            'peminjaman' => $peminjaman,
            'detailPeminjamans' => $detailPeminjamans,
            'id_barang_options' => $id_barang_options,
            'ruangan' => $ruangan,
            'ruangans' => $ruangans,
            
           
        ]);
    }

   

    public function fetchIdBarang($id_barang) {
        $id_ruangan_option = Inventaris::where('id_barang', $id_barang)
        ->select('id_ruangan') // Pilih kolom 'kondisi_barang' di sini
        ->distinct()
        ->with(['ruangan:id_ruangan,nama_ruangan']) // Specify the columns you want
        ->get();
    
        return response()->json($id_ruangan_option);
    }

    public function fetchNamaBarang($id_barang)
    {
        $namaBarangOptions = Inventaris::where('id_barang', $id_barang)
            ->select('id_barang') // Select both columns
            ->distinct()
            ->with(['barang:id_barang,nama_barang']) 
            ->get();
    
        return response()->json($namaBarangOptions);
    }

  

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_users' => 'nullable',
                'id_karyawan' => 'nullable',
                'id_guru' => 'nullable',
                'jurusan' => 'nullable',
                'kelas' => 'nullable',
                'status' => 'nullable',
                'keterangan_pemakaian' => 'nullable',
                'tgl_pinjam' => 'required|date',
                'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
    
        $id_users = $request->filled('id_users') ? $request->id_users : 1;
        $id_karyawan = $request->filled('id_karyawan') ? $request->id_karyawan : 1;
        $id_guru = $request->filled('id_guru') ? $request->id_guru : 1;
    
        if ($id_users == 1 && $id_karyawan == 1 && $id_guru == 1) {
            return response()->json(['error' => 'Nama Lengkap Belum Diisi.'], 400);
        }
    
        // Create new record
        $peminjaman = new Peminjaman([
            'id_users' => $id_users,
            'id_guru' => $id_guru,
            'id_karyawan' => $id_karyawan,
            'status' => $request->status,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'keterangan_pemakaian' => $request->keterangan_pemakaian,
        ]);
        $peminjaman->save();
        
        if (request()->ajax()) {
        return response()->json(['id_peminjaman' => $peminjaman->id_peminjaman, 'message' => 'Peminjaman berhasil disimpan']);
        }else{
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.'
    ]);
    }
    }

   
    public function update(Request $request, $id_peminjaman)
    {
        try {
            $request->validate([
                'id_users' => 'nullable',
                'id_karyawan' => 'nullable',
                'id_guru' => 'nullable',
                'jurusan' => 'nullable',
                'kelas' => 'nullable',
                'status' => 'nullable',
                'keterangan_pemakaian' => 'nullable',
                'tgl_pinjam' => 'required|date',
                'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
        
        // $id_peminjaman_session = session()->get('id_peminjaman', null);
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $id_users = $request->filled('id_users') ? $request->id_users : 1;
        $id_karyawan = $request->filled('id_karyawan') ? $request->id_karyawan : 1;
        $id_guru = $request->filled('id_guru') ? $request->id_guru : 1;
    
        if ($id_users == 1 && $id_karyawan == 1 && $id_guru == 1) {
            return response()->json(['error' => 'Nama Lengkap Belum Diisi.'], 400);
        }
        // Update existing record
        $peminjaman->update([
            'id_users' =>  $id_users,
            'id_guru' => $id_guru,
            'id_karyawan' =>  $id_karyawan,
            'status' => $request->status,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'keterangan_pemakaian' => $request->keterangan_pemakaian,
        ]);
        
        if (request()->ajax()) {
            return response()->json(['id_peminjaman' => $peminjaman->id_peminjaman, 'message' => 'Peminjaman berhasil disimpan']);
            }else{
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan.'
        ]);
        }
    }

    public function export(Request $request)
{
    // Set default date range if not provided
    $defaultStartDate = '2023-01-01';
    $defaultEndDate = '2023-12-31';

    // Get filter parameters from request or use default values
    $tglawal = $request->input('tglawal', $defaultStartDate);
    $tglakhir = $request->input('tglakhir', $defaultEndDate);

    // Initialize query builder
    $peminjamanQuery = Peminjaman::query();

    // Apply date range filter if provided
    if ($tglawal && $tglakhir) {
        $peminjamanQuery->whereBetween('tgl_pinjam', [$tglawal, $tglakhir]);
    }

    // Apply id_barang filter if provided
    $id_barang = $request->input('id_barang');
    if ($id_barang) {
        $peminjamanQuery->whereHas('detailPeminjaman.inventaris.barang', function ($query) use ($id_barang) {
            $query->where('id_barang', $id_barang);
        });
    }

    // Retrieve the filtered Peminjaman records
    $peminjaman = $peminjamanQuery->orderBy('id_peminjaman', 'desc')->get();

    // Download the report using the filtered data
    return Excel::download(new PeminjamanExport($peminjaman), 'peminjaman.xlsx');
}


    
    public function filter(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $barang_id = $request->input('barang_id');
    
        // Query to filter Peminjaman records
        $peminjamanQuery = Peminjaman::whereBetween('tgl_pinjam', [$tanggal_awal, $tanggal_akhir]);
    
        // If barang_id is provided, add condition to filter by barang_id
        if ($barang_id) {
            $peminjamanQuery->where('id_barang', $barang_id);
        }
    
        // Fetch filtered Peminjaman records
        $filteredPeminjaman = $peminjamanQuery->get();
    
        // Return the filtered Peminjaman records as JSON response or to a view
        return view('peminjaman.index', compact('filteredPeminjaman'));
    } 
  
 

    Public function destroy($id_peminjaman)
    {
        $peminjaman = Peminjaman::find($id_peminjaman);

     
        DetailPeminjaman::where('id_peminjaman', $id_peminjaman)->delete();
        $peminjaman->delete();
        if (request()->ajax()) {
        return response()->json(['success' => true, 'message' => 'Data telah terhapus.']);
        }else{
        return redirect()->route('peminjaman.index')->with('success_message', 'Data telah terhapus.');
        }
    }
    
}