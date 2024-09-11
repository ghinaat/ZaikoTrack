<?php

namespace App\Http\Controllers;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Inventaris;
use App\Models\Ruangan;
use App\Models\Notifikasi;
use App\Models\User;
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
                'ket_tidak_lengkap_awal' => 'nullable|max:100',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->with(['error' => $e->validator->errors()], 400);
        }
    
        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan kode_barang
        $inventaris = Inventaris::whereHas('barang',  function ($query) use ($request) {
            $query->where('kode_barang', $request->kode_barang);
        })->first();
    
        // Pastikan Inventaris ditemukan
        if (!$inventaris) {
             return redirect()->back()->with(['error' => 'Kode Barang tidak ditemukan.']);
        }
    
        // Validasi status peminjaman inventaris
        $existingDetailPeminjaman = DetailPeminjaman::where('id_inventaris', $inventaris->id_inventaris)
            ->where('status', '!=', 'sudah_dikembalikan')
            ->first();
    
        if ($existingDetailPeminjaman) {
             return redirect()->back()->with(['error' => 'Barang ini sudah dipinjam oleh pengguna lain.'], 400);
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
                'ket_tidak_lengkap_awal' => 'nullable|max:100',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->with(['error' => $e->validator->errors()], 400);
        }
    
        // Mendapatkan objek Inventaris berdasarkan id_ruangan dan kode_barang
        $inventaris = Inventaris::whereHas('barang', function ($query) use ($request) {
            $query->where('kode_barang', $request->kode_barang);
        })->first();
    
        // Pastikan Inventaris ditemukan
        if (!$inventaris) {
            return redirect()->back()->with(['error' => 'Kode Barang Tidak Ditemukan']);
        }
    
        // Validasi status peminjaman inventaris
        $existingDetailPeminjaman = DetailPeminjaman::where('id_inventaris', $inventaris->id_inventaris)
            ->where('status', '!=', 'sudah_dikembalikan')
            ->first();
    
        if ($existingDetailPeminjaman) {
            return redirect()->back()->with(['error' => 'Barang ini sudah dipinjam oleh pengguna lain.'], 400);
        }
    
        // Mendapatkan peminjaman berdasarkan id_peminjaman
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
    
        // Membuat detail peminjaman baru
        $detailPeminjaman = new DetailPeminjaman([
            'id_peminjaman' => $id_peminjaman,
            'id_inventaris' => $inventaris->id_inventaris,
            'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
            'kondisi_barang_akhir' => null,
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
                'ket_tidak_lengkap_awal' => 'nullable|max:100',
            ]);
        } catch (ValidationException $e) {
             return redirect()->back()->with(['error' => $e->validator->errors()], 400);
        }
    
        // Check if the id_barang already exists for the given id_peminjaman
        $existingDetail = DetailPeminjaman::where('id_peminjaman', $request->id_peminjaman)
            ->whereHas('inventaris', function ($query) use ($request) {
                $query->where('id_barang', $request->id_barang);
            })
            ->first();
    
        if ($existingDetail) {
             return redirect()->back()->with(['error' => 'Barang sudah ada di peminjaman ini.'], 400);
        }
    
       
        $inventaris = Inventaris::with(['ruangan', 'barang'])
            ->where('id_barang', $request->input('id_barang'))
            ->first();
    
       
        if (!$inventaris) {
             return redirect()->back()->with(['error' => 'Data tidak tersimpan.'], 400);
        }
    
        $peminjaman = Peminjaman::findOrFail($request->id_peminjaman);
        $detailPeminjaman = new DetailPeminjaman([
            'id_peminjaman' => $request->id_peminjaman,
            'id_inventaris' => $inventaris->id_inventaris,
            'ket_tidak_lengkap_awal' => $request->ket_tidak_lengkap_awal,
            'kondisi_barang_akhir' => null,
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
            'ket_tidak_lengkap_awal' => 'nullable|max:100',
        ]);

        $detailPeminjaman = DetailPeminjaman::find($id_detail_peminjaman);
      
        $detailPeminjaman->ket_tidak_lengkap_awal = $request->ket_tidak_lengkap_awal;

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
                'ket_tidak_lengkap_akhir' => 'nullable|max:100',
               
            ]);
            } catch (ValidationException $e) {
                return redirect()->back()->withErrors($e->validator)->withInput();
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
            $detailPeminjaman->kondisi_barang_akhir = $request->kondisi_barang_akhir;
            $detailPeminjaman->ket_tidak_lengkap_akhir = $request->ket_tidak_lengkap_akhir;
            $detailPeminjaman-> tgl_kembali = Carbon::now(); 

            if (auth()->user()->level === 'siswa') {
                $detailPeminjaman->status = 'proses_pengajuan';

                $pengguna = User::where('id_users', $peminjaman->id_users)->first();
                $notifikasi = new Notifikasi();
                $notifikasi->judul = 'Pengembalian Barang ';
                $notifikasi->pesan = 'Pengajuan Pengembalian Barang Peminjaman anda telah dikirmkan kepada teknisi. Dimohon untuk menunnggu konfirmasi lebih lanjut.';
                $notifikasi->is_dibaca = 'tidak_dibaca';
                $notifikasi->label = 'info';
                $notifikasi->send_email = 'yes';
                $notifikasi->id_users = $peminjaman->id_users; 
                $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
                $notifikasi->save();

                $notifikasiTeknisi = User::where('level', 'teknisi')->get();
            
                foreach ($notifikasiTeknisi as $na) {
                    $notifikasi = new Notifikasi();
                    $notifikasi->judul = 'Pengembalian Barang ';
                    $notifikasi->pesan =  'Pengajuan pengembalian baranng peminjaman oleh '.$pengguna->name.' telah diterima. Diharapkan untuk segera mengkonfirmasi pengajuan secepatnya.'; 
                    $notifikasi->is_dibaca = 'tidak_dibaca';
                    $notifikasi->label = 'info';
                    $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
                    $notifikasi->id_users = $na->id_users;
                    $notifikasi->save();
                }
            } else {
                $detailPeminjaman->status = 'sudah_dikembalikan';
            }

            $detailPeminjaman ->save();

          
            $id_peminjaman =   $detailPeminjaman -> id_peminjaman;
            
            

                return redirect()->route('peminjaman.showDetail', ["id_peminjaman" => $id_peminjaman])->with(['success_message' => 'Data telah tersimpan.'
            ]);
            
    
    }

    public function approval(Request $request, $id_detail_peminjaman)
    {

        $request->validate([
            'kondisi_barang_akhir' => 'required',
            'status' => 'required',
            'ket_ditolak_pengajuan' => 'nullable|max:100',
            'ket_tidak_lengkap_akhir' => 'nullable|max:100',
        ]);

        $detailPeminjaman = DetailPeminjaman::find($id_detail_peminjaman);
        $detailPeminjaman->kondisi_barang_akhir = $request->kondisi_barang_akhir;
        $detailPeminjaman->status = $request->status;
        $detailPeminjaman->ket_ditolak_pengajuan = $request->ket_ditolak_pengajuan;
        $detailPeminjaman->ket_tidak_lengkap_akhir = $request->ket_tidak_lengkap_akhir;

        $detailPeminjaman ->save();
        $peminjaman = Peminjaman::findOrFail($detailPeminjaman->id_peminjaman);

        if ($detailPeminjaman->status == 'pengajuan_ditolak') {
            $detailPeminjaman->kondisi_barang_akhir = null;
            $detailPeminjaman ->save();
            $pengguna = User::where('id_users', $peminjaman->id_users)->first();
            $notifikasi = new Notifikasi();
            $notifikasi->judul = 'Pengajuan Pengembalian Ditolak';
            $notifikasi->pesan = 'Pengajuan pengembalian barang pinjaman Anda telah ditolak oleh teknisi. Silakan hubungi teknisi untuk informasi lebih lanjut atau untuk mengajukan permohonan baru.';            
            $notifikasi->is_dibaca = 'tidak_dibaca';
            $notifikasi->label = 'info';
            $notifikasi->send_email = 'yes';
            $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
            $notifikasi->id_users = $pengguna->id_users;  // Include ID in the link            $notifikasi->id_users = $pengguna->id_users;
            $notifikasi->save();

            $notifikasiTeknisi = User::where('level', 'teknisi')->get();
        
            foreach ($notifikasiTeknisi as $na) {
                $notifikasi = new Notifikasi();
                $notifikasi->judul = 'Penolakan Pengembalian Barang';
                $notifikasi->pesan = 'Pesan pengembalian barang pinjaman oleh '.$pengguna->name.' ditolak telah dikirimkan. Silakan periksa dan tindak lanjuti sesuai prosedur yang berlaku.';                
                $notifikasi->is_dibaca = 'tidak_dibaca';
                $notifikasi->label = 'info';
                $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
                $notifikasi->id_users = $na->id_users;
                $notifikasi->save();
            }
        }
        if ($detailPeminjaman->status == 'sudah_dikembalikan') {
            $detailPeminjaman->kondisi_barang_akhir = null;
            $detailPeminjaman ->save();
            $pengguna = User::where('id_users', $peminjaman->id_users)->first();
            $notifikasi = new Notifikasi();
            $notifikasi->judul = 'Pengajuan Pengembalian Diterima';
            $notifikasi->pesan = 'Pengajuan pengembalian barang pinjaman Anda telah diterima oleh teknisi.';            
            $notifikasi->is_dibaca = 'tidak_dibaca';
            $notifikasi->label = 'info';
            $notifikasi->send_email = 'yes';
            $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
            $notifikasi->id_users = $pengguna->id_users;  // Include ID in the link            $notifikasi->id_users = $pengguna->id_users;
            $notifikasi->save();

            $notifikasiTeknisi = User::where('level', 'teknisi')->get();
        
            foreach ($notifikasiTeknisi as $na) {
                $notifikasi = new Notifikasi();
                $notifikasi->judul = 'Pengajuan Pengembalian Diterima';
                $notifikasi->pesan = 'Pesan pengembalian barang pinjaman oleh '.$pengguna->name.' diterima telah dikirimkan.';                
                $notifikasi->is_dibaca = 'tidak_dibaca';
                $notifikasi->label = 'info';
                $notifikasi->link = '/peminjaman/' . $detailPeminjaman->id_peminjaman;
                $notifikasi->id_users = $na->id_users;
                $notifikasi->save();
            }
        }
        
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.']);
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