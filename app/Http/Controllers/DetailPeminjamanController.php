<?php

namespace App\Http\Controllers;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class DetailPeminjamanController extends Controller
{

    public function store(Request $request)
    {

        $request->validate([
            'id_ruangan' => 'required',
            'id_barang' => 'required',
            'kondisi_barang' => 'required',
            'ket_tidak_lengkap_awal' => 'nullable',
            'jumlah_barang' => 'required',
        ]);

        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan id_barang
        $inventaris = Inventaris::where('id_ruangan', $request->input('id_ruangan'))
            ->where('id_barang', $request->input('id_barang')) ->where('kondisi_barang', $request->input('kondisi_barang'))
            ->first();

        // Pastikan Inventaris ditemukan sebelum melanjutkan
        if (!$inventaris) {
         
            return redirect()->back()->with(['error_message' => 'Data tidak tersimpan.',
        ]);
        }

        $detailPeminjaman = new DetailPeminjaman([
            'id_peminjaman' => $request->id_peminjaman,
            'id_inventaris' => $inventaris->id_inventaris,
            'jumlah_barang' => $request->jumlah_barang,
            'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
            'status' => 'dipinjam'
           

        ]);
        $detailPeminjaman ->save();

        return redirect()->back()->with([
            'success_message' => 'Data telah tersimpan.',
        ]);
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

        $inventaris = Inventaris::where('id_ruangan', $request->input('id_ruangan'))
        ->where('id_barang', $request->input('id_barang')) ->where('kondisi_barang', $request->input('kondisi_barang_akhir'))
        ->first();

        if (!$inventaris) {
            $inventaris = new Inventaris([
                'id_barang' => $detailPeminjaman->id_barang,
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

        if ($detailPeminjaman) {
          
            $detailPeminjaman->delete();
          
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }
}