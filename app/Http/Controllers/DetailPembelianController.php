<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelian;
use App\Models\JenisBarang;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class DetailPembelianController extends Controller
{
    public function showDetail( $id_pembelian){
        
        $pembelian = Pembelian::findOrFail($id_pembelian);
        $detailPembelian = DetailPembelian::with('barang.jenisbarang')->where('id_pembelian', $id_pembelian)->get();

        $alatPerlengkapan = Barang::where('id_jenis_barang', '!=', 3)->pluck('id_barang'); 
        $barangSudahDibeli = DetailPembelian::pluck('id_barang')->toArray();

        // Mengecualikan barang yang sudah tercantum dalam detail pembelian
        $selectedalatPerlengkapan = Barang::whereNotIn('id_barang', $barangSudahDibeli)
        ->whereIn('id_barang', $alatPerlengkapan)
        ->get();

        $bahanPraktik = Barang::where('id_jenis_barang',  3)->get();
        $barang = Barang::pluck('nama_barang', 'id_barang');

        // dd($selectedalatPerlengkapan);
        return view("pembelian.detail",[
            'pembelian' => $pembelian,
            'selectedalatPerlengkapan' => $selectedalatPerlengkapan,
            'bahanPraktik' => $bahanPraktik,
            'barang' => $barang,
            'detailPembelian' => $detailPembelian,
        ]);
    }

    public function store(Request $request, $id_pembelian){
        
        // dd($request);
        $request->validate([
            'id_pembelian'  => 'required',
            'id_jenis_barang' => 'required',
            'id_barang_perlengkapan'  => 'nullable',
            'id_barang_bahan'  => 'nullable',
            'jumlah_barang'  => 'nullable',
            'subtotal_pembelian'  => 'nullable',
        ]);
        
        $detailPembelian = new DetailPembelian();
        
        if ($request->id_jenis_barang == 3){
            $detailPembelian->id_barang = $request->id_barang_bahan;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $pembelian = Pembelian::findOrFail($id_pembelian);
            if($subtotalPembelians > ($pembelian->total_pembelian)){
                return redirect()->back()->with('error', 'Subtotal pembelian melebihi batas total pembelian!');}
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $subtotalPembelians / ($request->jumlah_barang);
        } else{
            $detailPembelian->id_barang = $request->id_barang_perlengkapan;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $pembelian = Pembelian::findOrFail($id_pembelian);
            if($subtotalPembelians > ($pembelian->total_pembelian)){
                return redirect()->back()->with('error', 'Subtotal pembelian melebihi batas total pembelian!');}
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $subtotalPembelians;
        }

        $detailPembelian->id_pembelian = $request->id_pembelian;
        $detailPembelian->jumlah_barang = $request->jumlah_barang;
        
        
        $detailPembelian->save();
        // dd($detailPembelian);
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        
    }

    public function update(Request $request, $id_detail_pembelian){
        
        // dd($request);
        $request->validate([
            'id_barang'  => 'required',
            'id_pembelian'  => 'required',
            'jumlah_barang'  => 'required',
            'subtotal_pembelian'  => 'required',
            // 'harga_perbarang'  => 'required',
        ]);
        
        $detailPembelian = DetailPembelian::find($id_detail_pembelian);
        $detailPembelian->id_barang = $request->id_barang;
        $detailPembelian->id_pembelian = $request->id_pembelian;
        $detailPembelian->jumlah_barang = $request->jumlah_barang;
        
        $detailPembelian->subtotal_pembelian = $request->subtotal_pembelian;

        $subtotal_pembelian = $request->subtotal_pembelian;
        $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
        $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
        $detailPembelian->subtotal_pembelian = $subtotalPembelians;

        $pembelian = Pembelian::findOrFail($detailPembelian->id_pembelian)->first();
        if($subtotalPembelians > ($pembelian->total_pembelian)){
            return redirect()->back()->with('error', 'Subtotal pembelian melebihi batas total pembelian!');
        }
        
        $hargaPerbarang = $subtotalPembelians / ($request->jumlah_barang);
        $detailPembelian->harga_perbarang = $hargaPerbarang;
        
        $detailPembelian->save();
        // dd($pembelian);
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        
    }

    public function destroy($id_detail_pembelian){
        
        $detailPembelian = DetailPembelian::find($id_detail_pembelian);
        if($detailPembelian){
            $detailPembelian->delete();
        }

        return redirect()->back()->with(['success_message' => 'Data telah terhapus.']);

    }
}