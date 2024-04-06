<?php

namespace App\Http\Controllers;
use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InventarisController extends Controller
{
    public function index(){

        
        $inventaris = Ruangan::all();

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
            'jumlah_barang' => 'nullable',
            'kondisi_barang' => 'required',
            'ket_barang' => 'nullable'
        ]);

        $existingInventaris = Inventaris::where('id_ruangan', $request->id_ruangan)
        ->where('id_barang', $request->id_barang)
        ->where('kondisi_barang', 'lengkap')
        ->first();

    if ($existingInventaris) {
        $existingInventaris->jumlah_barang = $existingInventaris->jumlah_barang + $request->jumlah_barang;        $existingInventaris->ket_barang = $request->ket_barang;
        $existingInventaris->save();

        return redirect()->back()->with(['success_message' => 'Data telah diperbarui.']);
    }

    $stokBarang = Barang::where('id_barang', $request->id_barang)->first();

    if (!$stokBarang || $stokBarang->stok_barang < $request->jumlah_barang) {
        return redirect()->back()->with(['error' => 'Stok barang tidak mencukupi.']);
    }
 
        $inventaris = new Inventaris();
        $inventaris->id_barang = $request->id_barang;
        $inventaris->id_ruangan = $request->id_ruangan;
        $inventaris->jumlah_barang = $request->jumlah_barang;
        $inventaris->kondisi_barang = $request->kondisi_barang;
        $inventaris->ket_barang = $request->ket_barang;
        
        $inventaris->save();

        $id_ruangan  = $inventaris->id_ruangan;

        return redirect()->route('inventaris.showDetail', ["id_ruangan" => $id_ruangan])
        ->with(['success_message' => 'Data telah tersimpan.']);

    }

    public function update(Request $request, $id_inventaris){
        
        $request->validate([
            'id_barang' => 'required',
            'id_ruangan' => 'required',
            'jumlah_barang' => 'nullable',
            'kondisi_barang' => 'required',
            'ket_barang' => 'nullable'
        ]);

        $inventaris = Inventaris::find($id_inventaris);

        $stokBarang = Barang::where('id_barang', $request->id_barang)->first();

        if (!$stokBarang || $stokBarang->stok_barang < $request->jumlah_barang) {
            return redirect()->back()->with(['error' => 'Stok barang tidak mencukupi.']);
        }
       
        $inventaris->id_barang = $request->id_barang;
        $inventaris->id_ruangan = $request->id_ruangan;
        $inventaris->jumlah_barang = $request->jumlah_barang;
        $inventaris->kondisi_barang = $request->kondisi_barang;
        $inventaris->ket_barang = $request->ket_barang;
        $inventaris->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.']);
    }

    public function barcode()
    {

    
        $id_ruangan = Ruangan::all();
        // dd($id_ruangan);

      
        return view('inventaris.barcode', [ 'id_ruangan'   => $id_ruangan]);
    }

 
    public function AddBarcode(Request $request)
    {

        // dd($request);
            $request->validate([
                'kode_barang' => 'required',
                'kondisi_barang' => 'required',
                'ket_barang' => 'nullable',
               'id_ruangan' => 'required',
            ]);
    
            // Mendapatkan objek Inventaris berdasarkan id_ruangan dan id_barang
            $id_barang = Barang::where('kode_barang', $request->kode_barang)->first();
            // Pastikan Inventaris ditemukan sebelum melanjutkan
            if (!$id_barang) {
                return response()->json(['error' => 'Data tidak tersimpan.',
            ], 400);
            }    
            
            $inventaris = new Inventaris();
                $inventaris->id_barang = $id_barang->id_barang;
                $inventaris->id_ruangan = $request->id_ruangan;
                $inventaris->kondisi_barang = $request->kondisi_barang;
                $inventaris->ket_barang = $request->ket_barang; 
                $inventaris ->save();
            
          
                $id_ruangan  = $inventaris->id_ruangan;

                return redirect()->route('inventaris.showDetail', ["id_ruangan" => $id_ruangan])
                ->with(['success_message' => 'Data telah tersimpan.']);
    
    }

    public function showDetail($id_ruangan)
    {
        
        $ruangan = Ruangan::findOrFail($id_ruangan);
    
      
        // Ambil semua barang yang terkait dengan inventaris melalui ruangan
        $barangsInRuangan = $ruangan->barang;
    
        $barangEdit = Barang::orderByRaw("LOWER(nama_barang)")->pluck('nama_barang', 'id_barang');
        
        $inventariss = Inventaris::all();
        $inventaris = Inventaris::whereHas('barang', function ($query) use ($id_ruangan) {
            $query->where('id_ruangan', $id_ruangan);
        })->get();

        $inventarisAlat = Inventaris::whereHas('barang', function ($query) use ($id_ruangan) {
            $query->where('id_ruangan', $id_ruangan)
                ->where('id_jenis_barang', '!=', 3); 
        })->get();
    
        $inventarisBahan = Inventaris::whereHas('barang', function ($query) use ($id_ruangan) {
            $query->where('id_ruangan', $id_ruangan)
                ->where('id_jenis_barang', 3); 
        })->get();

        $BarangAlat = Barang::where('id_jenis_barang', '!=', 3)->get();
        $Barangbahan = Barang::where('id_jenis_barang',  3)->get();

        $inventarisAlat->each(function ($item) {
            $item->status_pinjam = DetailPeminjaman::where('id_inventaris', $item->id_inventaris)
                                               ->where('status', 'dipinjam')
                                               ->exists();
        });
        
        return view('inventaris.show', [
            'inventaris' => $inventaris,
            'inventariss' => $inventariss,
            'ruangan' => $ruangan,
            'barangsInRuangan' => $barangsInRuangan,
            'barangEdit' => $barangEdit,
            'inventarisAlat' => $inventarisAlat,
            'inventarisBahan' => $inventarisBahan,
            'BarangAlat' => $BarangAlat,
            'Barangbahan' => $Barangbahan
        ]);
    }

    public function destroy($id_inventaris)
    {
    
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
            return redirect()->back()->with(['error' => 'Data tidak ditemukan.']);
        }
    }
    
    
    

    

}