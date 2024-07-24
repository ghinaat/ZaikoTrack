<?php

namespace App\Http\Controllers;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Inventaris;
use App\Models\Ruangan;
use App\Models\Notifikasi;
use App\Events\NotifPeminjaman;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class DetailPeminjamanController extends Controller
{


        public function AddBarcode(Request $request)
        {
            // Validasi input request
            try {
                $request->validate([
                'kode_barang' => 'required',
                'ket_tidak_lengkap_awal' => 'nullable',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        }
    
        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan kode_barang
        $inventaris = Inventaris::whereHas('barang',  function ($query) use ($request) {
            $query->where('kode_barang', $request->kode_barang);
        })->first();
    
        // Pastikan Inventaris ditemukan
        if (!$inventaris) {
            return response()->json(['error' => 'Kode Barang tidak ditemukan.'], 400);
        }
    
        // Validasi status peminjaman inventaris
        $existingDetailPeminjaman = DetailPeminjaman::where('id_inventaris', $inventaris->id_inventaris)
            ->where('status', 'dipinjam')
            ->first();
    
        if ($existingDetailPeminjaman) {
            return response()->json(['error' => 'Barang ini sudah dipinjam oleh pengguna lain.'], 400);
        }
    
        // Mendapatkan peminjaman berdasarkan id_peminjaman
        $peminjaman = Peminjaman::findOrFail($request->id_peminjaman);
    
        // Membuat detail peminjaman baru
        $detailPeminjaman = new DetailPeminjaman([
            'id_peminjaman' => $request->id_peminjaman,
            'id_inventaris' => $inventaris->id_inventaris,
            'ket_tidak_lengkap_awal' => $inventaris->ket_barang,
            'status' => 'dipinjam',
            'tgl_kembali' => $peminjaman->tgl_kembali,
        ]);
        $detailPeminjaman->save();
       

        // Mengambil nama barang dan ruangan
        $namaBarang = Inventaris::with(['barang'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
        $namaRuangan = Inventaris::with(['ruangan'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
    
        if (request()->ajax()) {
            if ($namaBarang && $namaRuangan) {
                return response()->json([
                    'nama_ruangan' => $namaRuangan->ruangan->nama_ruangan,
                    'nama_barang' => $namaBarang->barang->nama_barang,
                    'id_detail_peminjaman' => $detailPeminjaman->id_detail_peminjaman
                ]);
            } else {
                return response()->json(['error' => 'One or more relationships are null or undefined'], 400);
            }
        } else {
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan.']);
        }
    }

    public function AddQrcode(Request $request, $id_peminjaman)
    {
        // Validasi input request
        try {
            $request->validate([
                'kode_barang' => 'required',
                'ket_tidak_lengkap_awal' => 'nullable',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        }
    
        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan kode_barang
        $inventaris = Inventaris::whereHas('barang', function ($query) use ($request) {
            $query->where('kode_barang', $request->kode_barang);
        })->first();
    
        // Pastikan Inventaris ditemukan
        if (!$inventaris) {
            return response()->json(['error' => 'Data tidak tersimpan.'], 400);
        }
    
        // Validasi status peminjaman inventaris
        $existingDetailPeminjaman = DetailPeminjaman::where('id_inventaris', $inventaris->id_inventaris)
            ->where('status', 'dipinjam')
            ->first();
    
        if ($existingDetailPeminjaman) {
            return response()->json(['error' => 'Barang ini sudah dipinjam oleh pengguna lain.'], 400);
        }
    
        // Mendapatkan peminjaman berdasarkan id_peminjaman
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
    
        // Membuat detail peminjaman baru
        $detailPeminjaman = new DetailPeminjaman([
            'id_peminjaman' => $id_peminjaman,
            'id_inventaris' => $inventaris->id_inventaris,
            'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
            'status' => 'dipinjam',
            'tgl_kembali' => $peminjaman->tgl_kembali,
        ]);
        $detailPeminjaman->save();
        
    
        // Mengambil nama barang dan ruangan
        $namaBarang = Inventaris::with(['barang'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
        $namaRuangan = Inventaris::with(['ruangan'])->where('id_inventaris', $detailPeminjaman->id_inventaris)->first();
    
        if (request()->ajax()) {
            if ($namaBarang && $namaRuangan) {
                return response()->json([
                    'nama_ruangan' => $namaRuangan->ruangan->nama_ruangan,
                    'nama_barang' => $namaBarang->barang->nama_barang,
                    'id_detail_peminjaman' => $detailPeminjaman->id_detail_peminjaman
                ]);
            } else {
                return response()->json(['error' => 'One or more relationships are null or undefined'], 400);
            }
        } else {
            return redirect()->route('peminjaman.showDetail', ["id_peminjaman" => $id_peminjaman])
            ->with(['success_message' => 'Data telah tersimpan.']);
        }
    }

    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_barang' => 'required',
                'ket_tidak_lengkap_awal' => 'nullable',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 400);
        }
    
        // Check if the id_barang already exists for the given id_peminjaman
        $existingDetail = DetailPeminjaman::where('id_peminjaman', $request->id_peminjaman)
            ->whereHas('inventaris', function ($query) use ($request) {
                $query->where('id_barang', $request->id_barang);
            })
            ->first();
    
        if ($existingDetail) {
            return response()->json(['error' => 'Barang sudah ada di peminjaman ini.'], 400);
        }
    
       
        $inventaris = Inventaris::with(['ruangan', 'barang'])
            ->where('id_barang', $request->input('id_barang'))
            ->first();
    
       
        if (!$inventaris) {
            return response()->json(['error' => 'Data tidak tersimpan.'], 400);
        }
    
        $peminjaman = Peminjaman::findOrFail($request->id_peminjaman);
        $detailPeminjaman = new DetailPeminjaman([
            'id_peminjaman' => $request->id_peminjaman,
            'id_inventaris' => $inventaris->id_inventaris,
            'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
            'status' => 'dipinjam',
            'tgl_kembali' => $peminjaman->tgl_kembali,
        ]);
        $detailPeminjaman->save();
    
        // Fetching the nama_barang, nama_ruangan, and kode_barang
        $namaBarang = Inventaris::with(['barang'])
            ->where('id_inventaris', $detailPeminjaman->id_inventaris)
            ->first();
        $namaRuangan = Inventaris::with(['ruangan'])
            ->where('id_inventaris', $detailPeminjaman->id_inventaris)
            ->first();
    
        if (request()->ajax()) {
            if ($namaBarang && $namaRuangan) {
                return response()->json([
                    'nama_ruangan' => $namaRuangan->ruangan->nama_ruangan,
                    'nama_barang' => $namaBarang->barang->nama_barang,
                    'kode_barang' => $namaBarang->barang->kode_barang,
                    'id_detail_peminjaman' => $detailPeminjaman->id_detail_peminjaman
                ]);
            } else {
                return response()->json(['error' => 'One or more relationships are null or undefined'], 400);
            }
        } else {
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan.']);
        }
    }
    

    public function update(Request $request, $id_detail_peminjaman)
    {

        $request->validate([
            'id_barang' => 'required',
        ]);

        $detailPeminjaman = DetailPeminjaman::find($id_detail_peminjaman);
        // dd($detailPeminjaman);
        $inventaris = Inventaris::where('id_barang', $request->id_barang) 
        ->first();
        $existingInventaris = DetailPeminjaman::where('id_detail_peminjaman', '!=', $id_detail_peminjaman)
        ->whereHas('inventaris', function($query) use ($inventaris) {
            $query->where('id_inventaris', $inventaris->id_inventaris);
        })
        ->first();
        if ($existingInventaris) {
            return redirect()->back()->with(['error' => 'Barang ini telah dipinjam.']);
        }
        
        $detailPeminjaman-> id_inventaris = $inventaris->id_inventaris;
        $detailPeminjaman-> status = "dipinjam";
     
        $detailPeminjaman->ket_tidak_lengkap_akhir = $request->ket_tidak_lengkap_akhir;

        $detailPeminjaman ->save();
       

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.']);
    }

    public function Return($id_detail_peminjaman)
    {
        $detailPeminjamans = DetailPeminjaman::find($id_detail_peminjaman);
        $peminjaman = $detailPeminjamans->id_peminjaman;
        $ruangans = Ruangan::all();
      
    
        return view('peminjaman.return', [
            
            'detailPeminjamans' => $detailPeminjamans,
            'peminjaman' => $peminjaman,  
            'ruangans' => $ruangans,
            
           
        ]);
    }

    public function returnScan($id_detail_peminjaman)
    {
        $detailPeminjamans = DetailPeminjaman::find($id_detail_peminjaman);
        $peminjaman = $detailPeminjamans->id_peminjaman;
        $ruangans = Ruangan::all();
      
    
        return view('peminjaman.scan', [
            
            'detailPeminjamans' => $detailPeminjamans,
            'peminjaman' => $peminjaman,
            'ruangans' => $ruangans,
            
           
        ]);
    }

    

    public function returnBarcode(Request $request, $id_detail_peminjaman)
    {

        try{
            $request->validate([
                'kode_barang' => 'required',
                'kondisi_barang_akhir' => 'required',
                'ket_tidak_lengkap_akhir' => 'nullable',
               
            ]);
            } catch (ValidationException $e) {
                return response()->json(['error' => $e->validator->errors()], 400);
            }
            $detailPeminjaman = DetailPeminjaman::find($id_detail_peminjaman);

            if ($detailPeminjaman->inventaris->barang->kode_barang !== $request->kode_barang) {
              return redirect()->back()->with('error', 'Kode barang tidak sesuai dengan yang ada di detail peminjaman');
            }

            $peminjaman = Peminjaman::findOrFail($detailPeminjaman->id_peminjaman);
  
          
            
                    // Mendapatkan objek Inventaris berdasarkan id_ruangan dan id_barang
            $inventaris = Inventaris::whereHas('barang', function ($query) use ($request) {
                $query->where('kode_barang', $request->kode_barang);
            })->first();

            if (!$inventaris) {
                return redirect()->back()->with('error', 'Inventaris tidak ditemukan');
            }

            // Update id_ruangan inventaris ke id_ruangan yang baru
            $inventaris->id_ruangan = $request->id_ruangan;
            $inventaris->kondisi_barang = $request->kondisi_barang_akhir;
            $inventaris->ket_barang = $request->ket_tidak_lengkap_akhir;
            $inventaris->save();


            
            $detailPeminjaman-> id_inventaris = $inventaris->id_inventaris;
            $detailPeminjaman-> status = 'sudah_dikembalikan';
            $detailPeminjaman->kondisi_barang_akhir = $request->kondisi_barang_akhir;
            $detailPeminjaman->ket_tidak_lengkap_akhir = $request->ket_tidak_lengkap_akhir;
            $detailPeminjaman-> tgl_kembali = Carbon::now(); 

            $detailPeminjaman ->save();

          
            $id_peminjaman =   $detailPeminjaman -> id_peminjaman;
            
            

                return redirect()->route('peminjaman.showDetail', ["id_peminjaman" => $id_peminjaman])->with(['success_message' => 'Data telah tersimpan.'
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
            return redirect()->back()->with('success_message', 'Data telah terhapus.');
        }
    }
}