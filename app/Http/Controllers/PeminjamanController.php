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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    
    public function index(){
    
        $peminjaman = Peminjaman::all();

        $barang = Barang::all();

        $detailPeminjaman = DetailPeminjaman::all();
        $users = User::where('id_users', '!=', 1)  
        ->orderByRaw("LOWER(name)")  
        ->get(); 
        $guru = Guru::where('id_guru', '!=', 1)  
        ->orderByRaw("LOWER(nama_guru)")  
        ->get(); 
        $karyawan = Karyawan::where('id_karyawan', '!=', 1)  
        ->orderByRaw("LOWER(nama_karyawan)")  
        ->get(); 

        
      
        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'barang' => $barang,
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
            ->whereNotIn('barang.id_jenis_barang', [3]) // Exclude inventaris where id_jenis_barang is 3
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
      
        $barang =  Barang::where('id_jenis_barang', '!=', 3)->get();
    
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
            'barang' => $barang,
            'idPeminjaman' => $idPeminjaman,
            
        ]);
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
        ->whereNotIn('barang.id_jenis_barang', [3]) // Exclude inventaris where id_jenis_barang is 3
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

    public function fetchKondisiBarang($id_ruangan, $id_barang)
    {
        $kondisiBarangOptions = Inventaris::where('id_barang', $id_barang)->where('id_ruangan', $id_ruangan)
            ->select('id_inventaris', 'kondisi_barang', 'ket_barang') // Select both columns
            ->distinct()
            ->get();
    
        return response()->json($kondisiBarangOptions);
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
        $peminjaman = [];
    
        $defaultStartDate = '2023-01-01';
        $defaultEndDate = '2023-12-31';
    
        $tglawal = $request->input('tglawal', $defaultStartDate);
        $tglakhir = $request->input('tglakhir', $defaultEndDate);
    
        // Get peminjaman data within the date range
        $peminjamanData = Peminjaman::whereBetween('tgl_pinjam', [$tglawal, $tglakhir])->get();
    
        // Fetch details for each peminjaman
        foreach ($peminjamanData as $peminjamanItem) {
            $details = DetailPeminjaman::where('id_peminjaman', $peminjamanItem->id_peminjaman)->get();
            foreach ($details as $detail) {
                $inventaris = Inventaris::findOrFail($detail->id_inventaris);
                $detail->nama_barang = $inventaris->nama_barang;
            }
            $peminjamanItem->details = $details;
        }
    
        $peminjaman['data'] = $peminjamanData;
        $peminjaman['tglawal'] = $tglawal;
        $peminjaman['tglakhir'] = $tglakhir;
    
        // Download the report
        return Excel::download(new PeminjamanExport($peminjaman), 'peminjaman.xlsx');
    }

    public function filter(Request $request)
    {

        $peminjaman = [];

        $defaultStartDate = '2023-01-01';
        $defaultEndDate = '2023-12-31';

        $tglawal = $request->input('tglawal', $defaultStartDate);
        $tglakhir = $request->input('tglakhir', $defaultEndDate);

        $peminjaman['data'] = Peminjaman::whereBetween('tgl_pinjam', [$tglawal, $tglakhir])->get();

        $peminjaman['tglawal'] = $tglawal;
        $peminjaman['tglakhir'] = $tglakhir;

        return view('peminjaman.filter', [
            'peminjaman' => $peminjaman,
        ]);

    }

  
 

    Public function destroy($id_peminjaman)
    {
        $peminjaman = Peminjaman::find($id_peminjaman);

        if ($peminjaman) {
            DetailPeminjaman::where('id_peminjaman', $id_peminjaman)->delete();
            $peminjaman->delete();
            return response()->json(['success' => true, 'message' => 'Data telah terhapus.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Peminjaman not found'], 404);
        }
    
        

        return redirect()->route('peminjaman.index')->with('success_message', 'Data telah terhapus.');
    }
    
}