<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Inventaris;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemakaianController extends Controller
{

    public function index(){
        $groupedPemakaians = Pemakaian::select('tgl_pakai', 'nama_lengkap', 'kelas', 'jurusan','keterangan_pemakaian', DB::raw('MAX(id_pemakaian) as id_pemakaian'))
        ->groupBy('tgl_pakai', 'nama_lengkap', 'kelas', 'jurusan','keterangan_pemakaian' )
        ->get();
    
        // dd($groupedPemakaians);
        return view('pemakaian.index',[
            'groupedPemakaians' => $groupedPemakaians,
        ]);
    }

    public function showDetail($id_pemakaian){
        $pemakaian = Pemakaian::find($id_pemakaian);
        if ($pemakaian) {
            $tgl_pakai = $pemakaian->tgl_pakai;
            $nama_lengkap = $pemakaian->nama_lengkap;
    
            $detailPemakaians = Pemakaian::where('tgl_pakai', $tgl_pakai)
                ->where('nama_lengkap', $nama_lengkap)->with(['inventaris.barang'])
                ->get();
        }

        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();

        return view('pemakaian.show',[
            'detailpemakaian' => $detailPemakaians,
            'pemakaian' => $pemakaian,
            'barang' => $bahanPraktik,
        ]);
    }

    public function create(){
        $cart = Cart::with(['inventaris.barang', 'inventaris.ruangan'])->get();
        
        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();
            
            // dd($bahanPraktik);
        return view('pemakaian.create',[
            'barang' => $bahanPraktik,
            'cart' => $cart,
        ]);
    }

    // app/Http/Controllers/YourController.php
    public function getRuanganOptions($id_barang)
    {
        
        $ruanganOptions = Inventaris::where('id_barang', $id_barang)
        ->select('id_ruangan') 
        ->distinct()
        ->with(['ruangan:id_ruangan,nama_ruangan']) // Specify the columns you want
        ->get();
        // Kembalikan data dalam format JSON
        return response()->json($ruanganOptions);
    }

    public function storeDetail(Request $request, $id_pemakaian){
        $request->validate([
            'id_barang' => 'required',
            'id_ruangan' => 'required',
            'jumlah_barang' => 'required',
        ]);
        $id_inventaris = Inventaris::where('id_barang', $request->id_barang)->where('id_ruangan', $request->id_ruangan)->first();
        $datasiswa = Pemakaian::find($id_pemakaian);
        // dd($datasiswa);
        $pemakaian = new Pemakaian();
        $pemakaian->id_inventaris = $id_inventaris->id_inventaris;
        $pemakaian->nama_lengkap = $datasiswa->nama_lengkap;
        $pemakaian->kelas = $datasiswa->kelas;
        $pemakaian->jurusan = $datasiswa->jurusan;
        $pemakaian->jumlah_barang = $request->jumlah_barang;
        $pemakaian->keterangan_pemakaian = $datasiswa->keterangan_pemakaian;
        $pemakaian->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);

    }

    public function store(Request $request){
        $request->validate([
            'nama_lengkap' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'keterangan_pemakaian'

        ]);
        $cart = Cart::all();

        foreach($cart as $cr){
            $pemakaian = new Pemakaian();
            $pemakaian->id_inventaris = $cr->id_inventaris;
            $pemakaian->nama_lengkap = $request->nama_lengkap;
            $pemakaian->kelas = $request->kelas;
            $pemakaian->jurusan = $request->jurusan;
            $pemakaian->jumlah_barang = $cr->jumlah_barang;
            if($request->keterangan_pemakaian){
                $pemakaian->keterangan_pemakaian = $request->keterangan_pemakaian;
            }else{
                $pemakaian->keterangan_pemakaian = null;
            }
            $pemakaian->save();
        }
        Cart::truncate();
        return redirect('pemakaian')->with(['success_message' => 'Data telah tersimpan.',]);
    }
    

    public function update(Request $request, $id_pemakaian){
        $request->validate([
            'nama_lengkap' =>'required',
            'kelas' =>'required',
            'jurusan' =>'required',
            'keterangan' =>'nullable',
        ]);
        $pemakaian = Pemakaian::find($id_pemakaian);

        if ($pemakaian) {
            // Data ditemukan
            $tgl_pakai = $pemakaian->tgl_pakai;
            $nama_lengkap = $pemakaian->nama_lengkap;
    
            // Menggunakan nilai yang didapat untuk mencari data lain
            Pemakaian::where('tgl_pakai', $tgl_pakai)
                ->where('nama_lengkap', $nama_lengkap)
                ->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'kelas' => $request->kelas,
                    'jurusan' => $request->jurusan,
                    'keterangan_pemakaian' => $request->keterangan_pemakaian,
                ]);

                
        }
        return redirect('pemakaian')->with(['success_message' => 'Data telah tersimpan.',]);
    }

    public function destroy($id_pemakaian){
        $pemakaian = Pemakaian::find($id_pemakaian);
        if ($pemakaian) {
            // Data ditemukan
            $tgl_pakai = $pemakaian->tgl_pakai;
            $nama_lengkap = $pemakaian->nama_lengkap;
    
            // Menggunakan nilai yang didapat untuk mencari data lain
            Pemakaian::where('tgl_pakai', $tgl_pakai)
                ->where('nama_lengkap', $nama_lengkap)
                ->delete();
                
        }
        return redirect('pemakaian')->with(['success_message' => 'Data telah terhapus.',]);

    }

    public function  destroyDetail($id_pemakaian){
        $pemakaian = Pemakaian::find($id_pemakaian);
        
        if ($pemakaian) {
            $tgl_pakai = $pemakaian->tgl_pakai;
            $nama_lengkap = $pemakaian->nama_lengkap;

            $pemakaian->delete();

            $remainingRecords = Pemakaian::where('tgl_pakai', $tgl_pakai)
            ->where('nama_lengkap', $nama_lengkap)
            ->exists();
            if(!$remainingRecords){
                return redirect('pemakaian')->with(['success_message' => 'Data telah terhapus.',]);
            }else {
                return redirect()->back()->with(['success_message' => 'Data telah terhapus.',]);

            }

        }


    }
}