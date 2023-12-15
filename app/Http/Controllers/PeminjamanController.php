<?php

namespace App\Http\Controllers;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    
    public function index(){
    
        $peminjaman = Peminjaman::all();

        $ruangan = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_ruangan')
            ->with(['ruangan'])
            ->get();
        
        $id_barang_options = Inventaris::whereIn('id_ruangan', $ruangan->pluck('id_ruangan'))
            ->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')
            ->with(['barang']) // Assuming you have defined the relationship in the Inventaris model
            ->get();

        $barang = Barang::all();
        
        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'ruangan' => $ruangan,
            'id_barang_options' => $id_barang_options,
            'barang' => $barang
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

    public function cart(Request $request)
    {

        // dd($request);
        // Validasi request sesuai kebutuhan Anda
        $request->validate([
            'id_ruangan' => 'required',
            'id_barang' => 'required',
            'kondisi_barang' => 'required',
            'keterangan_pemakaian' => 'nullable',
        ]);

        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan id_barang
        $inventaris = Inventaris::where('id_ruangan', $request->input('id_ruangan'))
            ->where('id_barang', $request->input('id_barang')) ->where('kondisi_barang', $request->input('kondisi_barang'))
            ->first();

        // Pastikan Inventaris ditemukan sebelum melanjutkan
        if (!$inventaris) {
         
            return redirect()->back()->with(['error_message' => 'Data tidak tersimpan.',
        ]);
        }

        $cart = new Cart([
            'id_inventaris' => $inventaris->id_inventaris,
           

        ]);

        $cart ->save();


        // Redirect atau berikan respons sesuai kebutuhan
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.'
    ]);
    }

    public function store(Request $request)
    {

        // dd($request);
        // Validasi request sesuai kebutuhan Anda
        $request->validate([
            'id_ruangan' => 'required',
            'id_barang' => 'required',
            'nama_lengkap' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'keterangan_pemkaian' => 'nullable',
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required',
            'ket_tidak_lengkap_awal' => 'nullable',
            'jumlah_barang' => 'required',
        ]);

        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan id_barang
        $inventaris = Inventaris::where('id_ruangan', $request->input('id_ruangan'))
            ->where('id_barang', $request->input('id_barang'))
            ->first();

        // Pastikan Inventaris ditemukan sebelum melanjutkan
        if (!$inventaris) {
         
            return redirect()->back()->with(['error_message' => 'Data tidak tersimpan.',
        ]);
        }

        $peminjaman = new Peminjaman([
            'nama_lengkap' => $request->nama_lengkap,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
            'tgl_pinjam' => $request->tgl_pinjam,
            'keterangan_pemkaian' => $request->keterangan_pemkaian,

        ]);

        $peminjaman ->save();

        // Melakukan penyimpanan di tabel detail_peminjaman
        $detailPeminjaman = new DetailPeminjaman([
            'id_inventaris' => $inventaris->id_inventaris,
            'tgl_kembali' => $request->tgl_kembali,
            'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
            'jumlah_barang' => $request->jumlah_barang,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            // ... tambahkan atribut lainnya sesuai kebutuhan
        ]);

        $detailPeminjaman->save();

        // Redirect atau berikan respons sesuai kebutuhan
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.'
    ]);
    }
    
}