<?php

namespace App\Http\Controllers;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Siswa;
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

        
      
        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'barang' => $barang,
            'detailPeminjaman' => $detailPeminjaman,
           
        ]);
        
    }

    public function create()
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
      
        $barang = Barang::all();
        $siswa = Siswa::where('id_siswa', '!=', 1)  
        ->orderByRaw("LOWER(nama_siswa)")  
        ->get(); 
        $guru = Guru::where('id_guru', '!=', 1)  
        ->orderByRaw("LOWER(nama_guru)")  
        ->get(); 
        $karyawan = Karyawan::where('id_karyawan', '!=', 1)  
        ->orderByRaw("LOWER(nama_karyawan)")  
        ->get(); 
        $peminjamans = Peminjaman::with('detailPeminjaman')->get();
        $idPeminjaman = session('id_peminjaman');
        
        
        return view('peminjaman.create', [
            'peminjaman' => $peminjaman,
            'peminjamans' => $peminjamans,
            'siswa' => $siswa,
            'guru' => $guru,
            'karyawan' => $karyawan,
            'ruangan' => $ruangan,
            'id_barang_options' => $id_barang_options,
            'barang' => $barang,
            'idPeminjaman' => $idPeminjaman,
            
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
      
        $barang = Barang::all();
        $siswa = Siswa::where('id_siswa', '!=', 1)  
        ->orderByRaw("LOWER(nama_siswa)")  
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
            'siswa' => $siswa,
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
        ->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
        ->groupBy('id_barang')
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
        $kondisiBarangOptions = Inventaris::where('id_ruangan', $id_ruangan)
            ->where('id_barang', $id_barang)
            ->select('id_inventaris', 'kondisi_barang', 'ket_barang') // Select both columns
            ->distinct()
            ->get();
    
        return response()->json($kondisiBarangOptions);
    }

  

    public function store(Request $request)
    {

    //   dd($request);
    try{
        $request->validate([
            'id_siswa' => 'nullable',
            'id_karyawan' => 'nullable',
            'id_guru' => 'nullable',
            'jurusan' => 'nullable',
            'kelas' => 'nullable',
            'status' => 'nullable',
            'keterangan_pemakaian' => 'nullable',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_peminjaman',
        ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
        $id_siswa = $request->filled('id_siswa') ? $request->id_siswa : 1;
        $id_karyawan = $request->filled('id_karyawan') ? $request->id_karyawan : 1;
        $id_guru = $request->filled('id_guru') ? $request->id_guru : 1;

        if ($id_siswa == 1 && $id_karyawan == 1 && $id_guru == 1) {
            return response()->json(['error' => 'Nama Lengkap Belum Diisi.'], 400);
        }
        
        $peminjaman = new Peminjaman([
          'id_siswa' => $id_siswa,
            'id_guru' => $id_guru,
            'id_karyawan' => $id_karyawan,
            'status' =>  $request->status,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'keterangan_pemakaian' => $request->keterangan_pemakaian,

        ]);

        $peminjaman ->save();
        $idPeminjaman = $peminjaman->id_peminjaman;

        return response()->json(['id_peminjaman' => $idPeminjaman, 'message' => 'Peminjaman berhasil disimpan']);
    
    }

   
    public function update(Request $request)
    {

    //   dd($request);
    try{
        $request->validate([
            'id_siswa' => 'nullable',
            'id_karyawan' => 'nullable',
            'id_guru' => 'nullable',
            'jurusan' => 'nullable',
            'kelas' => 'nullable',
            'status' => 'required',
            'keterangan_pemakaian' => 'nullable',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
        ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
        
        $id_siswa = $request->filled('id_siswa') ? $request->id_siswa : 1;
        $id_karyawan = $request->filled('id_karyawan') ? $request->id_karyawan : 1;
        $id_guru = $request->filled('id_guru') ? $request->id_guru : 1;

        if ($id_siswa == 1 && $id_karyawan == 1 && $id_guru == 1) {
            return response()->json(['error' => 'Nama Lengkap Belum Diisi.'], 400);
        }
        $idPeminjaman = session('id_peminjaman');
        $peminjaman = $request->id_peminjaman;

        $peminjaman-> id_siswa = $id_siswa;
        $peminjaman-> id_karyawan = $id_karyawan;
        $peminjaman-> id_guru = $id_guru;
        $peminjaman->status = $request->status;
        $peminjaman->jurusan = $request->jurusan;
        $peminjaman->kelas = $request->kelas;
        $peminjaman->tgl_pinjam = $request->tgl_pinjam;
        $peminjaman->tgl_kembali = $request->tgl_kembali;
        $peminjaman->keterangan_pemakaian = $request->keterangan_pemakaian;


        $peminjaman ->save();

        if (request()->ajax()) {
            return response()->json(['id_peminjaman' => $idPeminjaman]);

        }else{
        return redirect()->route('peminjaman.index')->with(['success_message' => 'Data telah tersimpan.'       
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

        $peminjaman['data'] = Peminjaman::whereBetween('tgl_pinjam', [$tglawal, $tglakhir])->get();

        $peminjaman['tglawal'] = $tglawal;
        $peminjaman['tglakhir'] = $tglakhir;

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
          
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }
    
}