<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelian;
use App\Models\JenisBarang;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;




class DetailPembelianController extends Controller
{
    public function showDetail( $id_pembelian){
        
        $pembelian = Pembelian::findOrFail($id_pembelian);
        $detailPembelian = DetailPembelian::with(['barang.jenisbarang'])->where('id_pembelian', $id_pembelian)->get();
        $idBarangs = $detailPembelian->pluck('id_barang');  
        $barangs = Barang::whereIn('id_barang', $idBarangs)->get();
        $jenisBarang = JenisBarang::all();
        $bahanPraktik = Barang::where('id_jenis_barang',  3)->get();
        $subtotalPembelian = DetailPembelian::where('id_pembelian', $id_pembelian)->sum('subtotal_pembelian');
        $jumlahPembelian = DetailPembelian::where('id_pembelian', $id_pembelian)->sum('jumlah_barang');
        // dd($barang);
        return view("pembelian.detail",[
            'pembelian' => $pembelian,
            'subtotalPembelian' => $subtotalPembelian,
            'bahanPraktik' => $bahanPraktik,
            'jenisBarang' => $jenisBarang,
            'detailPembelian' => $detailPembelian,
            'jumlahPembelian' => $jumlahPembelian,
            'barangs' => $barangs,
        ]);
    }

    public function getIdBarang($id_detail_pembelian){
        $barangSebelumnya = DetailPembelian::where('id_detail_pembelian', $id_detail_pembelian)->pluck('id_barang');
        $alatPerlengkapan = Barang::with(['jenisbarang'])->where('id_barang', $barangSebelumnya)->pluck('id_jenis_barang'); 
        $barangSudahDibeli = DetailPembelian::pluck('id_barang')->toArray();
        $selectUpdate = Barang::where(function($query) use ($barangSudahDibeli, $barangSebelumnya) {
            $query->whereNotIn('id_barang', $barangSudahDibeli)
                  ->orWhereIn('id_barang', $barangSebelumnya);
        })
        ->where('id_jenis_barang', '!=', 3)
        ->get();
    
        
        return response()->json([
            'selectUpdate' => $selectUpdate,
            'barangSebelumnya' => $barangSebelumnya,
            'alatPerlengkapan' => $alatPerlengkapan
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
            'merek' => 'nullable',
        ]);
        
        $detailPembelian = new DetailPembelian();
        
        if ($request->id_jenis_barang == 3){
            $barang = Barang::find($request->id_barang_bahan);

            $detailPembelian->id_pembelian = $request->id_pembelian;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $subtotalPembelians / ($request->jumlah_barang);
            $detailPembelian->jumlah_barang = $request->jumlah_barang;
            $detailPembelian->id_barang = $request->id_barang_bahan;
            $detailPembelian->save();
            
            if ($barang) {
                $barang->stok_barang += $request->jumlah_barang;
                $barang->save();
            }


        } else{
            $detailPembelian->id_pembelian = $request->id_pembelian;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $subtotalPembelians / ($request->jumlah_barang);
            $detailPembelian->jumlah_barang = $request->jumlah_barang;
            $detailPembelian->id_barang = null;
            $detailPembelian->save();

            
            $jumlahDataBarang = Barang::max('id_barang') ?? 0;
            $tahunPembelian = Pembelian::where('id_pembelian', $detailPembelian->id_pembelian)->value(DB::raw('YEAR(tgl_pembelian)'));
            $barangData = [];
            for ($i = 1; $i <= $request->jumlah_barang; $i++) {                $id_barang_perlengkapan = $request->id_barang_perlengkapan;
                $firstChar = substr($id_barang_perlengkapan, 0, 1);
                $withoutVowels = preg_replace('/[aeiou]/i', '', substr($id_barang_perlengkapan, 1));
                $nextTwoChars = substr($withoutVowels, 0, 2);
                $cutString = $firstChar . $nextTwoChars;
                $kode_barang = $cutString . '-' . '0' . $request->id_jenis_barang . $i . $tahunPembelian;

                // Periksa apakah kode_barang sudah ada dalam tabel barang
                if (Barang::where('kode_barang', $kode_barang)->exists()) {
                    $kode_barang_terbesar = Barang::where('nama_barang', $request->id_barang_perlengkapan)->max('kode_barang');
            
                    $posisi_tanda = strpos($kode_barang_terbesar, '-') + 3; // Mendapatkan posisi dua angka setelah tanda -
                    $panjang_kode = strlen($kode_barang_terbesar); // Mendapatkan panjang kode barang
                    $nomor_urut = substr($kode_barang_terbesar, $posisi_tanda, $panjang_kode - $posisi_tanda - 4); // Mengambil nomor urut
                
                
                    // Buat kode barang baru dengan nomor urut yang baru
                    $kodeBarang = $cutString . '-' . '0' . $request->id_jenis_barang . $nomor_urut + $i . $tahunPembelian;
                }else{
                    $kodeBarang = $cutString . '-' . '0' . $request->id_jenis_barang . $i . $tahunPembelian;
                }       

                $imageName = $this->generateBarcode($kode_barang);
                $barangData[] = [
                    'id_detail_pembelian' => $detailPembelian->id_detail_pembelian,
                    'id_barang' => $jumlahDataBarang + $i, // Mulai dari id_barang berikutnya
                    'id_jenis_barang' => $request->id_jenis_barang,
                    'nama_barang' => $request->id_barang_perlengkapan,
                    'merek' => $request->merek,
                    'kode_barang' => $kodeBarang,
                    'stok_barang' => null,
                    'qrcode_image' => $imageName,
                ];
            }
            
            // Simpan data barang
            Barang::insert($barangData);
            // dd($barangData);
        }

        
        
        // dd($detailPembelian);
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        
    }

    public function generateBarcode($kode_barang)
    {
        $ImageName = 'qrcode_' . $kode_barang;
        
        if (!empty($kode_barang)) {
            $qrCode = QrCode::create($kode_barang)->setSize(200)->setMargin(5);
            $storagePath = public_path('storage/qrcode/' . $ImageName . '.png');
            
            // Create a PngWriter instance
            $writer = new PngWriter();
            $qrCodeData = $writer->write($qrCode)->getString();
            // dd($qrCodeData);
    
            file_put_contents($storagePath, $qrCodeData);
    
            // Return the filename (including the extension) for further use if needed
            return $ImageName . '.png';
        } else {
            // Jika kode barang kosong, kembalikan null
            return null;
        }

    }
    public function update(Request $request, $id_detail_pembelian){
        
        // dd($request);
        $request->validate([
            'id_pembelian'  => 'required',
            'id_jenis_barang' => 'required',
            'id_barang_perlengkapan'  => 'nullable',
            'id_barang_bahan'  => 'nullable',
            'jumlah_barang'  => 'nullable',
            'subtotal_pembelian'  => 'nullable',
            'merek' => 'nullable',
        ]);
        // dd($request->id_jenis_barang);
        
        $detailPembelian = DetailPembelian::find($id_detail_pembelian);
        $barangAlat = Barang::where('id_detail_pembelian', $id_detail_pembelian)->first();
        // dd($barangAlat);

        if ($barangAlat && $request->id_jenis_barang != 3) {
            $detailPembelian->id_pembelian = $request->id_pembelian;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $request->jumlah_barang != 0 ? $subtotalPembelians / $request->jumlah_barang : 0;
            $detailPembelian->jumlah_barang = $request->jumlah_barang;
            $detailPembelian->id_barang = null;
            $detailPembelian->save();
        
            // Menggunakan metode static delete() pada model Barang
            Barang::where('id_detail_pembelian', $id_detail_pembelian)->delete();

            $jumlahDataBarang = Barang::max('id_barang') ?? 0;
            $tahunPembelian = Pembelian::where('id_pembelian', $detailPembelian->id_pembelian)->value(DB::raw('YEAR(tgl_pembelian)'));
            $barangData = [];
            for ($i = 1; $i <= $request->jumlah_barang; $i++) {
                $id_barang_perlengkapan = $request->id_barang_perlengkapan;
                $firstChar = substr($id_barang_perlengkapan, 0, 1);
                $withoutVowels = preg_replace('/[aeiou]/i', '', substr($id_barang_perlengkapan, 1));
                $nextTwoChars = substr($withoutVowels, 0, 2);
                $cutString = $firstChar . $nextTwoChars;
                $kode_barang = $cutString . '-' . '0' . $request->id_jenis_barang . $i . $tahunPembelian;

                if (Barang::where('kode_barang', $kode_barang)->exists()) {
                    $kode_barang_terbesar = Barang::where('nama_barang', $request->id_barang_perlengkapan)->max('kode_barang');
            
                    $posisi_tanda = strpos($kode_barang_terbesar, '-') + 3; // Mendapatkan posisi dua angka setelah tanda -
                    $panjang_kode = strlen($kode_barang_terbesar); // Mendapatkan panjang kode barang
                    $nomor_urut = substr($kode_barang_terbesar, $posisi_tanda, $panjang_kode - $posisi_tanda - 4); // Mengambil nomor urut
                    $kodeBarang = $cutString . '-' . '0' . $request->id_jenis_barang . $nomor_urut + $i . $tahunPembelian;
                }else{
                    $kodeBarang = $cutString . '-' . '0' . $request->id_jenis_barang . $i . $tahunPembelian;
                }       

                $imageName = $this->generateBarcode($kode_barang);
                $barangData[] = [
                    'id_detail_pembelian' => $detailPembelian->id_detail_pembelian,
                    'id_barang' => $jumlahDataBarang + $i, // Mulai dari id_barang berikutnya
                    'id_jenis_barang' => $request->id_jenis_barang,
                    'nama_barang' => $request->id_barang_perlengkapan,
                    'merek' => $request->merek,
                    'kode_barang' => $kodeBarang,
                    'stok_barang' => null,
                    'qrcode_image' => $imageName,
                ];
            }

            Barang::insert($barangData);
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        }else if($barangAlat && $request->id_jenis_barang == 3){

            $barang = Barang::find($request->id_barang_bahan);
            $detailPembelian->id_pembelian = $request->id_pembelian;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $subtotalPembelians / ($request->jumlah_barang);
            $detailPembelian->jumlah_barang = $request->jumlah_barang;
            $detailPembelian->id_barang = $request->id_barang_bahan;
            $detailPembelian->save();

            Barang::where('id_detail_pembelian', $id_detail_pembelian)->delete();

            if ($barang) {
                $barang->stok_barang += $request->jumlah_barang;
                $barang->save();
            }
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        }else if($barangAlat == null && $request->id_jenis_barang == 3){
            
            $barang = Barang::find($request->id_barang_bahan);
            if ($barang) {
                // Kurangi stok barang dengan jumlah barang dari detail pembelian
                $barang->stok_barang -= $detailPembelian->jumlah_barang;
                $barang->save();

                // Kurangi lagi stok barang dengan jumlah barang yang diminta sekarang
                $barang->stok_barang += $request->jumlah_barang;
                $barang->save();
            }
            $detailPembelian->id_pembelian = $request->id_pembelian;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $subtotalPembelians / ($request->jumlah_barang);
            $detailPembelian->jumlah_barang = $request->jumlah_barang;
            $detailPembelian->id_barang = $request->id_barang_bahan;
            $detailPembelian->save();

           
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        }else if($barangAlat == null && $request->id_jenis_barang !== 3){
            
            $barang = Barang::find($request->id_barang_bahan);
            if ($barang) {
                // Kurangi stok barang dengan jumlah barang dari detail pembelian
                $barang->stok_barang -= $detailPembelian->jumlah_barang;
                $barang->save();
            }
            $detailPembelian->id_pembelian = $request->id_pembelian;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "",  $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $request->jumlah_barang != 0 ? $subtotalPembelians / $request->jumlah_barang : 0;
            $detailPembelian->jumlah_barang = $request->jumlah_barang;
            $detailPembelian->nama_barang = null;
            $detailPembelian->save();

            
            $jumlahDataBarang = Barang::max('id_barang') ?? 0;
            $tahunPembelian = Pembelian::where('id_pembelian', $detailPembelian->id_pembelian)->value(DB::raw('YEAR(tgl_pembelian)'));
            $barangData = [];
            for ($i = 1; $i <= $request->jumlah_barang; $i++) {
                $id_barang_perlengkapan = $request->id_barang_perlengkapan;
                $firstChar = substr($id_barang_perlengkapan, 0, 1);
                $withoutVowels = preg_replace('/[aeiou]/i', '', substr($id_barang_perlengkapan, 1));
                $nextTwoChars = substr($withoutVowels, 0, 2);
                $cutString = $firstChar . $nextTwoChars;
                $kode_barang = $cutString . '-' . '0' . $request->id_jenis_barang . $i . $tahunPembelian;

                if (Barang::where('kode_barang', $kode_barang)->exists()) {
                    $kode_barang_terbesar = Barang::where('nama_barang', $request->id_barang_perlengkapan)->max('kode_barang');
            
                    $posisi_tanda = strpos($kode_barang_terbesar, '-') + 3; // Mendapatkan posisi dua angka setelah tanda -
                    $panjang_kode = strlen($kode_barang_terbesar); // Mendapatkan panjang kode barang
                    $nomor_urut = substr($kode_barang_terbesar, $posisi_tanda, $panjang_kode - $posisi_tanda - 4); // Mengambil nomor urut
                    $kodeBarang = $cutString . '-' . '0' . $request->id_jenis_barang . $nomor_urut + $i . $tahunPembelian;
                }else{
                    $kodeBarang = $cutString . '-' . '0' . $request->id_jenis_barang . $i . $tahunPembelian;
                }       

                $imageName = $this->generateBarcode($kode_barang);
                $barangData[] = [
                    'id_detail_pembelian' => $detailPembelian->id_detail_pembelian,
                    'id_barang' => $jumlahDataBarang + $i, // Mulai dari id_barang berikutnya
                    'id_jenis_barang' => $request->id_jenis_barang,
                    'nama_barang' => $request->id_barang_perlengkapan,
                    'merek' => $request->merek,
                    'kode_barang' => $kodeBarang,
                    'stok_barang' => null,
                    'qrcode_image' => $imageName,
                ];
            }

            Barang::insert($barangData);
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        }
        
        return redirect()->back()->with(['error' => 'Data tdiak tersimpan']);



        
    }

    public function destroy($id_detail_pembelian){
        // dd($id_detail_pembelian);
        $detailPembelian = DetailPembelian::find($id_detail_pembelian);
        if($detailPembelian){

            $barangWithDetail = Barang::where('id_detail_pembelian', $id_detail_pembelian)->first();
            if($barangWithDetail){
                Barang::where('id_detail_pembelian', $id_detail_pembelian)->delete();
            }else if($barangWithDetail == null){
                $barang = Barang::find($detailPembelian->id_barang);
                // dd($detailPembelian);

                    if ($barang) {
                        $barang->stok_barang -= $detailPembelian->jumlah_barang;
                        $barang->save();
                    }
            }
            $detailPembelian->delete();

        }

        return redirect()->back()->with(['success_message' => 'Data telah terhapus.']);

    }
}