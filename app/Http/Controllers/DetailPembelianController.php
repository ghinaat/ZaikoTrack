<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelian;
use App\Models\JenisBarang;
use App\Models\Inventaris;
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
            $inventarisData = Inventaris::where('id_barang', $request->id_barang_bahan)->where('id_ruangan', 3)->first();
            if($inventarisData){
                $inventarisData->jumlah_barang += $request->jumlah_barang;
                $inventarisData->save();
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
            for ($i = 1; $i <= $request->jumlah_barang; $i++) {
                $id_barang_perlengkapan = $request->id_barang_perlengkapan;
                $firstChar = substr($id_barang_perlengkapan, 0, 1);
                $withoutVowels = preg_replace('/[aeiou]/i', '', substr($id_barang_perlengkapan, 1));
                $nextTwoChars = substr($withoutVowels, 0, 2);
                $cutString = $firstChar . $nextTwoChars;
                $kode_barang = $cutString . '-' . '0' . $request->id_jenis_barang . $i . $tahunPembelian;
              

                if (Barang::where('kode_barang', $kode_barang)->exists()) {
                    $kode_barang_terbesar = Barang::where('nama_barang', $request->id_barang_perlengkapan)
                    ->max(DB::raw("LPAD(SUBSTRING(kode_barang, 5), 8, '0')"));
                    $nomor_urut = substr($kode_barang_terbesar, 2, -4); // Mengambil nomor urut
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
            foreach ($barangData as $barangItem) {
                $inventaris = new Inventaris();
                $inventaris->id_barang = $barangItem['id_barang'];
                $inventaris->jumlah_barang = null; // Assuming initial quantity is 0
                $inventaris->kondisi_barang = 'lengkap'; // Assuming default status is 'Baru'
                $inventaris->id_ruangan = 3;
                $inventaris->save();
              }
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
                    $kode_barang_terbesar = Barang::where('nama_barang', $request->id_barang_perlengkapan)
                    ->max(DB::raw("LPAD(SUBSTRING(kode_barang, 5), 8, '0')"));
                    $nomor_urut = substr($kode_barang_terbesar, 2, -4); // Mengambil nomor urut
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
            foreach ($barangData as $barangItem) {
                $inventaris = new Inventaris();
                $inventaris->id_barang = $barangItem['id_barang'];
                $inventaris->jumlah_barang = null; // Assuming initial quantity is 0
                $inventaris->kondisi_barang = 'lengkap'; // Assuming default status is 'Baru'
                $inventaris->id_ruangan = 3;
                $inventaris->save();
              }
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        }else if($barangAlat && $request->id_jenis_barang == 3){

            $barang = Barang::find($request->id_barang_bahan);
          
            $detailPembelian->id_pembelian = $request->id_pembelian;
            $oldJumlahBarang = $detailPembelian->jumlah_barang;
            $subtotal_pembelian = $request->subtotal_pembelian;
            $subtotalPembelian = str_replace(".", "", $subtotal_pembelian);
            $subtotalPembelians = str_replace("Rp", "", $subtotalPembelian);
            $detailPembelian->subtotal_pembelian = $subtotalPembelians;
            $detailPembelian->harga_perbarang = $subtotalPembelians / ($request->jumlah_barang);
            $detailPembelian->jumlah_barang = $request->jumlah_barang;
            $detailPembelian->id_barang = $request->id_barang_bahan;
            $detailPembelian->save();
        
            // Delete previous Barang records related to the detail_pembelian
            Barang::where('id_detail_pembelian', $id_detail_pembelian)->delete();
        
            if ($barang) {
                // Calculate the difference between the new and old jumlah_barang
                $newJumlahBarang = $request->jumlah_barang;
                $stokDifference = $newJumlahBarang - $oldJumlahBarang;

                        // Update the barang table with the new stock value
                $barang->stok_barang = $stokDifference;
                $barang->save();

                // Synchronize the stok_barang in the inventaris table
                $inventarisData = Inventaris::where('id_barang', $barang->id_barang)->where('id_ruangan', 3)->first();

                if ($stokDifference > 0) {
                    // If new stock is more than old stock, add the difference to inventaris
                    if ($inventarisData) {
                        $inventarisData->jumlah_barang += $stokDifference;
                        $inventarisData->save();
                    } else {
                        // If no existing record in inventaris, create a new one
                        Inventaris::create([
                            'id_barang' => $barang->id_barang,
                            'jumlah_barang' => $newJumlahBarang,
                            'kondisi_barang' => 'lengkap', // Assuming default status is 'lengkap'
                            'id_ruangan' => 3,
                        ]);
                    }
                } elseif ($stokDifference < 0) {
                    // If new stock is less than old stock, subtract the difference from inventaris
                    $inventarisData->jumlah_barang += $stokDifference; // $stokDifference is negative, so it effectively subtracts
                    $inventarisData->save();

                    // Handle the case where the difference is more than the available inventaris stock
                    if ($inventarisData->jumlah_barang < 0) {
                        // Calculate the excess that needs to be subtracted from the barang stock
                        $excess = abs($inventarisData->jumlah_barang);
                        $inventarisData->jumlah_barang = 0; // Set inventaris stock to 0
                        $inventarisData->save();

                        // Subtract the excess from barang stock
                        $barang->stok_barang -= $excess;
                        $barang->save();
                    }
                }
            }
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan']);

        }else if($barangAlat == null && $request->id_jenis_barang == 3){
            
            $barang = Barang::find($request->id_barang_bahan);
          
            $detailPembelian->id_pembelian = $request->id_pembelian;
            $oldJumlahBarang = $detailPembelian->jumlah_barang;
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
                // Calculate the difference between the new and old jumlah_barang
                $newJumlahBarang = $request->jumlah_barang;
                $stokDifference = $newJumlahBarang - $oldJumlahBarang;

                        // Update the barang table with the new stock value
                $barang->stok_barang = $stokDifference;
                $barang->save();

                // Synchronize the stok_barang in the inventaris table
                $inventarisData = Inventaris::where('id_barang', $barang->id_barang)->where('id_ruangan', 3)->first();

                if ($stokDifference > 0) {
                    // If new stock is more than old stock, add the difference to inventaris
                    if ($inventarisData) {
                        $inventarisData->jumlah_barang += $stokDifference;
                        $inventarisData->save();
                    } else {
                        // If no existing record in inventaris, create a new one
                        Inventaris::create([
                            'id_barang' => $barang->id_barang,
                            'jumlah_barang' => $newJumlahBarang,
                            'kondisi_barang' => 'lengkap', // Assuming default status is 'lengkap'
                            'id_ruangan' => 3,
                        ]);
                    }
                } elseif ($stokDifference < 0) {
                    // If new stock is less than old stock, subtract the difference from inventaris
                    $inventarisData->jumlah_barang += $stokDifference; // $stokDifference is negative, so it effectively subtracts
                    $inventarisData->save();

                    // Handle the case where the difference is more than the available inventaris stock
                    if ($inventarisData->jumlah_barang < 0) {
                        // Calculate the excess that needs to be subtracted from the barang stock
                        $excess = abs($inventarisData->jumlah_barang);
                        $inventarisData->jumlah_barang = 0; // Set inventaris stock to 0
                        $inventarisData->save();

                        // Subtract the excess from barang stock
                        $barang->stok_barang -= $excess;
                        $barang->save();
                    }
                }
            }
            
            

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
                    $kode_barang_terbesar = Barang::where('nama_barang', $request->id_barang_perlengkapan)
                    ->max(DB::raw("LPAD(SUBSTRING(kode_barang, 5), 8, '0')"));
                    $nomor_urut = substr($kode_barang_terbesar, 2, -4); // Mengambil nomor urut
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