<?php

namespace App\Http\Controllers;
use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index(){

        $inventaris = Inventaris::all();

        return view('inventaris.index',[
            'inventaris' => $inventaris,
            'ruangan'   => Ruangan::all(),
            'barang'    => Barang::all()
        ]);
    }

  
    public function store(Request $request){
        
        // dd($request);
        $request->validate([
            'id_barang' => 'required',
            'id_ruangan' => 'required',
            'jumlah_barang' => 'required',
            'kondisi_barang' => 'required',
            'ket_barang' => 'required'
        ]);

        $inventaris = new Inventaris();
        $inventaris->id_barang = $request->id_barang;
        $inventaris->id_ruangan = $request->id_ruangan;
        $inventaris->jumlah_barang = $request->jumlah_barang;
        $inventaris->kondisi_barang = $request->kondisi_barang;
        $inventaris->ket_barang = $request->ket_barang;
        $inventaris->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',
        ]);

    }

    public function show($id_kegiatan)
    {
        // Mengambil kegiatan berdasarkan id_kegiatan
        $kegiatan = Kegiatan::findOrFail($id_kegiatan);

        // Mengambil semua data user yang belum terkait dengan TimKegiatan
        $users = User::all();

        $peran = Peran::all();


        return view('kegiatan.show', [
            'kegiatan' => $kegiatan,
            'users' => $users,
            'timkegiatan' => $timkegiatan,
            'peran' => $peran,
        ]);
    }

}