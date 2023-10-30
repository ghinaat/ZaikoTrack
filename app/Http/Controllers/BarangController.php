<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(){

        $barang = Barang::all();
        return view('barang.index',[
        'barang' => $barang,
        'jenisBarang' => JenisBarang::all(),
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
}
