<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(){

        $barang = Barang::all();
        $inventaris = Inventaris::all();
        $totals = $inventaris->groupBy('id_barang')->map(function ($group) {
            return $group->sum('jumlah_barang');
        });

          // Create an associative array where keys are id_barang and values are updated stok_barang
        $updatedStokBarang = $barang->mapWithKeys(function ($barangItem) use ($totals) {
            $id_barang = $barangItem->id_barang;
            $total = $totals->get($id_barang, 0); // Get the total or default to 0
            return [$id_barang => $barangItem->stok_barang - $total];
        }); 

        return view('barang.index',[
        'barang' => $barang,
        'jenisBarang' => JenisBarang::all(),
        'updatedStokBarang' => $updatedStokBarang,
        'totals' => $totals,
        ]);
    }

    public function store(Request $request){
        // dd($request);
        $request->validate([
            'nama_barang' => 'required',
            'merek' => 'required',
            'stok_barang' => 'required',
            'id_jenis_barang'  => 'required',
        ]);

        $barang = new Barang();
        $barang->nama_barang = $request->nama_barang;
        $barang->merek = $request->merek;
        $barang->stok_barang = $request->stok_barang;
        $barang->id_jenis_barang = $request->id_jenis_barang;
        $barang->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',
    ]);
    }

    public function update(Request $request, $id_barang){
        $request->validate([
            'nama_barang' => 'required',
            'merek' => 'required',
            'stok_barang' => 'required',
            'id_jenis_barang'  => 'required',
        ]);

        $barang = Barang::find($id_barang);
        $barang->nama_barang = $request->nama_barang;
        $barang->merek = $request->merek;
        $barang->stok_barang = $request->stok_barang;
        $barang->id_jenis_barang = $request->id_jenis_barang;
        $barang->save();
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',
    ]);
    }

    public function destroy($id_barang){
        $barang = Barang::find($id_barang);
        if($barang){
            $barang->delete();
        }
        return redirect()->back()->with(['success_message' => 'Data telah terhapus.',]);
    }
}