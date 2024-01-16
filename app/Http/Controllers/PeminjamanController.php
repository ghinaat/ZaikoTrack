<?php

namespace App\Http\Controllers;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    
    public function index(){
    
        $peminjaman = Peminjaman::all();

        $ruangan = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_ruangan')
            ->with(['ruangan'])
            ->get();
        
        $id_barang_options = Inventaris::whereIn('id_ruangan', $ruangan->pluck('id_ruangan'))
            ->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')
            ->with(['barang']) // Assuming you have defined the relationship in the Inventaris model
            ->get();

        $barang = Barang::all();
        $cart = Cart::all();
        
        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'ruangan' => $ruangan,
            'id_barang_options' => $id_barang_options,
            'barang' => $barang,
            'cart' => $cart
        ]);
        
    }

    public function create()
    {
        $peminjaman = Peminjaman::all();
        
        // Get the maximum id_inventaris for each id_ruangan
        $ruangan = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_ruangan')
            ->get();
    
        // Get the maximum id_inventaris for each id_barang within the selected id_ruangan
        $id_barang_options = Inventaris::whereIn('id_ruangan', $ruangan->pluck('id_ruangan'))
            ->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')
            ->get();
    
        $barang = Barang::all();
        $cart = Cart::all();
        
        return view('peminjaman.create', [
            'peminjaman' => $peminjaman,
            'ruangan' => $ruangan,
            'id_barang_options' => $id_barang_options,
            'barang' => $barang,
            'cart' => $cart
        ]);
    }
    
    public function showDetail($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        $detailPeminjamans = DetailPeminjaman::where('id_peminjaman', $id_peminjaman)->get();
        $ruangan = Inventaris::select('id_ruangan', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
        ->groupBy('id_ruangan')
        ->get();
        $id_barang_options = Inventaris::whereIn('id_ruangan', $ruangan->pluck('id_ruangan'))
        ->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
        ->groupBy('id_barang')
        ->get();
        $ruangans = Ruangan::all();
    
        return view('peminjaman.show', [
            'peminjaman' => $peminjaman,
            'detailPeminjamans' => $detailPeminjamans,
            'id_barang_options' => $id_barang_options,
            'ruangan' => $ruangan,
            'ruangans' => $ruangans,
           
        ]);
    }

    public function fetchIdBarang($id_barang) {
        $id_ruangan_option = Inventaris::where('id_barang', $id_barang)
        ->select('id_ruangan') // Pilih kolom 'kondisi_barang' di sini
        ->distinct()
        ->with(['ruangan:id_ruangan,nama_ruangan']) // Specify the columns you want
        ->get();
    
        return response()->json($id_ruangan_option);
    }

    public function fetchKondisiBarang($id_ruangan, $id_barang)
    {
        $kondisiBarangOptions = Inventaris::where('id_ruangan', $id_ruangan)
            ->where('id_barang', $id_barang)
            ->select('id_inventaris', 'kondisi_barang', 'ket_barang') // Select both columns
            ->distinct()
            ->get();
    
        return response()->json($kondisiBarangOptions);
    }

    public function cart(Request $request)
    {

        // dd($request);
        // Validasi request sesuai kebutuhan Anda
        $request->validate([
            'id_ruangan' => 'required',
            'id_barang' => 'required',
            'kondisi_barang' => 'required',
            'ket_barang' => 'nullable',
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

        

        $cart = new Cart([
            'id_inventaris' => $inventaris->id_inventaris,
            'jumlah_barang' => $request->jumlah_barang,
            'ket_barang' => $request->ket_barang,
           

        ]);
        $cart ->save();

        return redirect()->back()->with([
            'success_message' => 'Data telah tersimpan.',
        ]);
    }

    public function store(Request $request)
    {

    //   dd($request);
        $request->validate([
            'nama_lengkap' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'keterangan_pemakaian' => 'nullable',
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required',
            'ket_tidak_lengkap_awal' => 'nullable',
        ]);

        $peminjaman = new Peminjaman([
            'nama_lengkap' => $request->nama_lengkap,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'keterangan_pemakaian' => $request->keterangan_pemakaian,

        ]);

        $peminjaman ->save();

        foreach (Cart::all() as $cartItem) {
            $detailPeminjaman = new DetailPeminjaman([
                'id_inventaris' => $cartItem->id_inventaris,
                'ket_tidak_lengkap_awal' => $cartItem->ket_barang,
                'jumlah_barang' => $cartItem->jumlah_barang,
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'status' => 'dipinjam'
            ]);
    
            // Save each DetailPeminjaman
            $detailPeminjaman->save();
        }
    
        // Clear the cart after processing
        Cart::truncate();

        // Redirect atau berikan respons sesuai kebutuhan
        return redirect()->route('peminjaman.index')->with(['success_message' => 'Data telah tersimpan.'
    ]);
    }

    public function update(Request $request, $id_peminjaman)
    {

    //   dd($request);
        $request->validate([
            'nama_lengkap' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'keterangan_pemakaian' => 'nullable',
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required',
        ]);

        $peminjaman = Peminjaman::find($id_peminjaman);

        $peminjaman-> nama_lengkap = $request->nama_lengkap;
        $peminjaman->   jurusan = $request->jurusan;
        $peminjaman->kelas = $request->kelas;
        $peminjaman->tgl_pinjam = $request->tgl_pinjam;
        $peminjaman->tgl_kembali = $request->tgl_kembali;
        $peminjaman->keterangan_pemakaian = $request->keterangan_pemakaian;


        $peminjaman ->save();

      
        return redirect()->route('peminjaman.index')->with(['success_message' => 'Data telah tersimpan.'
    ]);
    }


    Public function destroyCart($id_cart)
    {
        $cart = Cart::find($id_cart);
        if ($cart) {
            $cart->delete();
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }

    Public function clearCart()
    {
        Cart::truncate();
        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }

    Public function destroy($id_peminjaman)
    {
        $peminjaman = Peminjaman::find($id_peminjaman);

        if ($peminjaman) {
            DetailPeminjaman::where('id_peminjaman', $id_peminjaman)->delete();
            $peminjaman->delete();
          
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }
    
}