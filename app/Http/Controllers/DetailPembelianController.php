<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelian;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class DetailPembelianController extends Controller
{
    public function showDetail( $id_pembelian){
        
        $pembelian = Pembelian::findOrFail($id_pembelian);
        $detailPembelian = DetailPembelian::with('barang')->where('id_pembelian', $id_pembelian)->get();
        $barangs = Barang::all();
        $barang = Barang::pluck('nama_barang', 'id_barang');
        return view("pembelian.detail",[
            'pembelian' => $pembelian,
            'barangs' => $barangs,
            'barang' => $barang,
            'detailPembelian' => $detailPembelian,
        ]);
    }

    public function store(Request $request, $id_pembelian){
        
        // dd($request);
        $request->validate([
            'id_barang'  => 'required',
            'id_pembelian'  => 'required',
            'jumlah_barang'  => 'required',
            'subtotal_pembelian'  => 'required',
            // 'harga_perbarang'  => 'required',
        ]);
        
        $detailPembelian = new DetailPembelian();
        $detailPembelian->id_barang = $request->id_barang;
        $detailPembelian->id_pembelian = $request->id_pembelian;
        $detailPembelian->jumlah_barang = $request->jumlah_barang;
        
        $subtotal_pembelian = $request->subtotal_pembelian;
        $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
        $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
        $detailPembelian->subtotal_pembelian = $subtotalPembelians;

        $pembelian = Pembelian::findOrFail($id_pembelian);
        if($subtotalPembelians > ($pembelian->total_pembelian)){
            return redirect()->back()->with('error', 'Subtotal pembelian melebihi batas total pembelian!');
        }
        
        $hargaPerbarang = $subtotalPembelians / ($request->jumlah_barang);
        $detailPembelian->harga_perbarang = $hargaPerbarang;
        
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