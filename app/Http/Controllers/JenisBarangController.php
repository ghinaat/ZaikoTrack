<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    public function index(){

        $jenisBarang = JenisBarang::all();

        return view('jenisbarang.index',[
            'jenisBarang' => $jenisBarang,
        ]);
    }

    public function store(Request $request){
        
        // dd($request);
        $request->validate([
            'nama_jenis_barang' => 'required'
        ]);

        $jenisBarang = new JenisBarang();
        $jenisBarang->nama_jenis_barang = $request->nama_jenis_barang;
        $jenisBarang->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',
        ]);

    }

    public function update(Request $request, $id_jenis_barang){

        $request->validate([
            'nama_jenis_barang' => 'required',
        ]);

        $jenisBarang = JenisBarang::find($id_jenis_barang);

        $jenisBarang->nama_jenis_barang = $request->nama_jenis_barang;
        $jenisBarang->save();

        return redirect()->back()->with(['success_message' => 'Data telah terubah.',
    ]);

    }

    public function destroy($id_jenis_barang){
        $jenisBarang = JenisBarang::find($id_jenis_barang);
        if ($jenisBarang){
            $jenisBarang->delete();
        }
        return redirect()->back()->with(['success_message' => 'Data telah terhapus.',]);
    }
}
