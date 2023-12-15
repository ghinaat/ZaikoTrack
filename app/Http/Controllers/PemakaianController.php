<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemakaianController extends Controller
{

    public function index(){
        $pemakaian = Pemakaian::with(['inventaris.barang'])->get();
        $barang = Inventaris::with(['barang'])->get();

        $hasilQuery = Inventaris::select('id_barang', DB::raw('GROUP_CONCAT(id_ruangan) AS ruangan_terkait'))
            ->groupBy('id_barang')
            ->havingRaw('COUNT(DISTINCT id_ruangan) > 1')
            ->get();


        dd($hasilQuery);
        // dd($pemakaian);

        return view('pemakaian.index',[
            'pemakaian' => $pemakaian,
            'barang' => $barang,
            // 'namaBarang' => $namaBarang,
        ]);
    }

    public function store(Request $request){
        // dd($request);
        $request->validate([
            'id_inventaris' => 'required',
            'nama_lengkap' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'jumlah_barang' => 'required',
            'keterangan_pemakaian' => 'nullable',
        ]);

        $pemakaian = new Pemakaian();
        $pemakaian->id_inventaris = $request->id_inventaris;
        $pemakaian->nama_lengkap = $request->nama_lengkap;
        $pemakaian->kelas = $request->kelas;
        $pemakaian->jurusan = $request->jurusan;
        $pemakaian->jumlah_barang = $request->jumlah_barang;

        if($request->keterangan_pemakaian){
            $pemakaian->keterangan_pemakaian = $request->keterangan_pemakaian;
        }else{
            $pemakaian->keterangan_pemakaian = null;
        }
        $pemakaian->save();
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);
    }

}