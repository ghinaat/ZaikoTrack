<?php

namespace App\Http\Controllers;
use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InventarisController extends Controller
{
    public function index(){

        
        $inventaris = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as id_inventaris'))
        ->groupBy('id_ruangan')
        ->with(['ruangan'])
        ->get();
    

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
            'ket_barang' => 'nullable'
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

    public function update(Request $request, $id_inventaris){
        
        $request->validate([
            'id_barang' => 'required',
            'id_ruangan' => 'required',
            'jumlah_barang' => 'required',
            'kondisi_barang' => 'required',
            'ket_barang' => 'nullable'
        ]);

        $inventaris = Inventaris::find($id_inventaris);
       
        $inventaris->id_barang = $request->id_barang;
        $inventaris->id_ruangan = $request->id_ruangan;
        $inventaris->jumlah_barang = $request->jumlah_barang;
        $inventaris->kondisi_barang = $request->kondisi_barang;
        $inventaris->ket_barang = $request->ket_barang;
        $inventaris->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.']);
    }

    public function showDetail($id_ruangan)
    {
        // Ambil data inventaris berdasarkan ID
        $ruangan = Ruangan::findOrFail($id_ruangan);
    
      
        // Ambil semua barang yang terkait dengan inventaris melalui ruangan
        $barangsInRuangan = $ruangan->barang;
        $barangs = Barang::all();
        $barangEdit = Barang::orderByRaw("LOWER(nama_barang)")->pluck('nama_barang', 'id_barang');
        
        $inventariss = Inventaris::all();
        $inventaris = Inventaris::whereHas('barang', function ($query) use ($id_ruangan) {
            $query->where('id_ruangan', $id_ruangan);
        })->get();
        return view('inventaris.show', [
            'inventaris' => $inventaris,
            'inventariss' => $inventariss,
            'ruangan' => $ruangan,
            'barangsInRuangan' => $barangsInRuangan,
            'barangs' => $barangs,
            'barangEdit' => $barangEdit
        ]);
    }

    public function destroy($id_inventaris)
    {
        // Ambil data inventaris berdasarkan ID
        $inventaris = Inventaris::findOrFail($id_inventaris);
    
        if ($inventaris){
            $inventaris -> delete();
        }
       
        return redirect()->back()->with(['success_message' => 'Data telah terhapus.',]);

    }

    public function destroyRuangan($id_ruangan)
    {
        // Delete all records with the specified id_ruangan
        $deletedRows = Inventaris::where('id_ruangan', $id_ruangan)->delete();
        
        if ($deletedRows > 0) {
            return redirect()->back()->with(['success_message' => 'Data telah terhapus.']);
        } else {
            return redirect()->back()->with(['error_message' => 'Data tidak ditemukan.']);
        }
    }
    
    
    

    

}