<?php

namespace App\Http\Controllers;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class DetailPeminjamanController extends Controller
{


    public function AddBarcode(Request $request)
    {

        try{
            $request->validate([
                'kode_barang' => 'required',
                'ket_tidak_lengkap_awal' => 'nullable',
               
            ]);
            } catch (ValidationException $e) {
                return response()->json(['error' => $e->validator->errors()], 400);
            }
    
            // Mendapatkan objek Inventaris berdasarkan id_ruangan dan id_barang
            $inventaris = Inventaris::whereHas('barang', function ($query) use ($request) {
                $query->where('kode_barang', $request->kode_barang);
            })->first();
            
            // Pastikan Inventaris ditemukan sebelum melanjutkan
            if (!$inventaris) {
                return response()->json(['error' => 'Data tidak tersimpan.',
            ], 400);
            }
    
            $stokBarang = Inventaris::where('id_barang', $request->id_barang)->first();
    
            if (!$stokBarang || $stokBarang->jumlah_barang < $request->jumlah_barang) {
                return response()->json(['error' => 'Stok barang tidak mencukupi.'], 400);
            }
    
         
            $detailPeminjaman = new DetailPeminjaman([
                'id_peminjaman' => $request->id_peminjaman,
                'id_inventaris' => $inventaris->id_inventaris,
                'jumlah_barang' => $request->jumlah_barang,
                'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
                'status' => 'dipinjam'
    
            ]);
            $detailPeminjaman ->save();
            
            $namaBarang = Inventaris::with(['barang'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
            $namaRuangan = Inventaris::with(['ruangan'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
            if (request()->ajax()) {
                if ($namaBarang && $namaRuangan) {
                    return response()->json([
                        'nama_ruangan'  => $namaRuangan->ruangan->nama_ruangan,
                        'nama_barang' => $namaBarang->barang->nama_barang,
                        'jumlah_barang' => $detailPeminjaman->jumlah_barang,
                        'id_detail_peminjaman' => $detailPeminjaman->id_detail_peminjaman
                    ]);
                } else {
                    return response()->json(['error' => 'One or more relationships are null or undefined'],400);
                }
            }else{
                return redirect()->back()->with(['success_message' => 'Data telah tersimpan.'
            ]);
            }
    
    }

    public function store(Request $request)
    {
        try{
        $request->validate([
            'id_ruangan' => 'required',
            'id_barang' => 'required',
            'kondisi_barang' => 'required',
            'ket_tidak_lengkap_awal' => 'nullable',
            'jumlah_barang' => 'required',
        ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        }

        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan id_barang
        $inventaris = Inventaris::with(['ruangan', 'barang'])
        ->where('id_ruangan', $request->input('id_ruangan'))
        ->where('id_barang', $request->input('id_barang'))
        ->where('kondisi_barang', $request->input('kondisi_barang'))
        ->first();
        
        // Pastikan Inventaris ditemukan sebelum melanjutkan
        if (!$inventaris) {
         
            return response()->json(['error' => 'Data tidak tersimpan.',
        ], 400);
        }

        $stokBarang = Inventaris::where('id_barang', $request->id_barang)->first();

        if (!$stokBarang || $stokBarang->jumlah_barang < $request->jumlah_barang) {
            return response()->json(['error' => 'Stok barang tidak mencukupi.'], 400);
        }

     
        $detailPeminjaman = new DetailPeminjaman([
            'id_peminjaman' => $request->id_peminjaman,
            'id_inventaris' => $inventaris->id_inventaris,
            'jumlah_barang' => $request->jumlah_barang,
            'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
            'status' => 'dipinjam'
           

        ]);
        $detailPeminjaman ->save();
        
        $namaBarang = Inventaris::with(['barang'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
        $namaRuangan = Inventaris::with(['ruangan'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
        if (request()->ajax()) {
            if ($namaBarang && $namaRuangan) {
                return response()->json([
                    'nama_ruangan'  => $namaRuangan->ruangan->nama_ruangan,
                    'nama_barang' => $namaBarang->barang->nama_barang,
                    'jumlah_barang' => $detailPeminjaman->jumlah_barang,
                    'id_detail_peminjaman' => $detailPeminjaman->id_detail_peminjaman
                ]);
            } else {
                return response()->json(['error' => 'One or more relationships are null or undefined'],400);
            }
        }else{
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan.'
        ]);
        }
        
    }

    public function update(Request $request, $id_detail_peminjaman)
    {

        $request->validate([
            'id_ruangan' => 'required',
            'status' => 'required',
            'kondisi_barang_akhir' => 'required',
            'ket_tidak_lengkap_akhir' => 'nullable',
        ]);

        $detailPeminjaman = DetailPeminjaman::find($id_detail_peminjaman);
        // dd($detailPeminjaman);
        $inventaris = Inventaris::where('id_ruangan', $request->input('id_ruangan'))
        ->where('id_barang', $detailPeminjaman->inventaris->id_barang) ->where('kondisi_barang', $request->input('kondisi_barang_akhir'))
        ->first();

        
        if (!$inventaris) {
            $inventaris = new Inventaris([
                'id_barang' => $detailPeminjaman->inventaris->id_barang,
                'id_ruangan' => $request->id_ruangan,
                'kondisi_barang' => $request->kondisi_barang_akhir,
                'jumlah_barang' => $detailPeminjaman->jumlah_barang,
                'ket_barang' => $request->ket_tidak_lengkap_akhir,
            ]);
            $inventaris ->save();
        }
        
        $detailPeminjaman-> id_inventaris = $inventaris->id_inventaris;
        $detailPeminjaman-> status = $request->status;
        $detailPeminjaman->kondisi_barang_akhir = $request->kondisi_barang_akhir;
        $detailPeminjaman->ket_tidak_lengkap_akhir = $request->ket_tidak_lengkap_akhir;

        $detailPeminjaman ->save();

       
      
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.'
    ]);
    }

 
    Public function destroy($id_detail_peminjaman)
    {
        $detailPeminjaman = DetailPeminjaman::find($id_detail_peminjaman);

        if (!$detailPeminjaman) {
            // Handle if the record is not found
            if (request()->ajax()) {
                return response()->json(['error' => 'Record not found.'], 404);
            } else {
                return redirect()->back()->with('error_message', 'Record not found.');
            }
        }

            $detailPeminjaman->delete();
          

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
                'id_detail_peminjaman' => $detailPeminjaman->id_detail_peminjaman,
            ]);
            
        } else {
            return redirect()->back()->with('success_message', 'Data has been deleted.');
        }
    }
}