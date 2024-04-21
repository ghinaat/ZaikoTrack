<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Notifikasi;
use App\Models\Inventaris;
use Endroid\QrCode\QrCode;
use PDF;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

class BarangController extends Controller
{
    public function index(){

        $alatdanperlengkapan = Barang::with('inventaris')->where('id_jenis_barang', '!=', 3)->get();

        $bahan = Barang::where('id_jenis_barang',  3)->get();
        $barang = Barang::all();
        $inventaris = Inventaris::all();
        $totals = $inventaris->groupBy('id_barang')->map(function ($group) {
            return $group->sum('jumlah_barang');
        });
        // dd($alatdanperlengkapan);
          // Create an associative array where keys are id_barang and values are updated stok_barang
          $updatedStokBarang = $totals->map(function ($jumlah_barang, $id_barang) {
            $barang = Barang::find($id_barang); // Assuming you have a model named Barang for the barang table
            $updatedStok = $barang->stok_barang + $jumlah_barang;
            return $updatedStok;
        });

        return view('barang.index',[
        'barang' => $barang,
        'bahan' => $bahan,
        'alatdanperlengkapan' => $alatdanperlengkapan,
        'jenisBarang' => JenisBarang::all(),
        'updatedStokBarang' => $updatedStokBarang,
        'totals' => $totals,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'merek' => 'required',
            'kode_barang' => 'nullable',
            'stok_barang' => 'nullable',
            'id_jenis_barang' => 'required',
        ]);

        $barang = new Barang();
        $barang->nama_barang = $request->nama_barang;
        $barang->merek = $request->merek;
        $barang->stok_barang = $request->stok_barang;
        $barang->kode_barang = $request->kode_barang;
        $barang->id_jenis_barang = $request->id_jenis_barang;
        // Generate QR code and get the image name if kode_barang is not empty
        $imageName = $barang->kode_barang ? $this->generateBarcode($barang->kode_barang) : null;

        // Update path QR code pada barang yang baru dibuat
        $barang->qrcode_image = $imageName;

        if($request->id_jenis_barang !== '3' && $request->kode_barang == null){
        return redirect()->back()->with(['error_message' => 'Data belum terisi.']);
        }else{
        $barang->save();

        $pengguna = auth()->user();

        $notifikasi = new Notifikasi();
        $notifikasi->judul = 'Data Barang';
        $notifikasi->pesan = 'Barang Baru Telah Berhasil Ditambahkan';
        $notifikasi->is_dibaca = 'tidak_dibaca';
        $notifikasi->send_email = 'yes';
        $notifikasi->label = 'info';
        $notifikasi->link = '/Barang';
        $notifikasi->id_users = $pengguna->id_users;
        $notifikasi->save();

        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.']);

    }
}

    public function generateBarcode($kode_barang)
    {
        $ImageName = 'qrcode_' . $kode_barang;
        
        if (!empty($kode_barang)) {
            $qrCode = QrCode::create($kode_barang)->setSize(200)->setMargin(5);
            $storagePath = public_path('storage/barcode/' . $ImageName . '.png');
            
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
    public function update(Request $request, $id_barang){
        $request->validate([
            'nama_barang' => 'required',
            'merek' => 'required',
            'kode_barang' => 'nullable',
            'stok_barang' => 'nullable',
            'id_jenis_barang' => 'required',
        ]);

        $barang = Barang::find($id_barang);
        $barang->nama_barang = $request->nama_barang;
        $barang->merek = $request->merek;
        $barang->stok_barang = $request->stok_barang;
        $barang->kode_barang = $request->kode_barang;
        $barang->id_jenis_barang = $request->id_jenis_barang;

        $imageName = $barang->kode_barang ? $this->generateBarcode($barang->kode_barang) : null;

        // Update path QR code pada barang yang baru dibuat
        $barang->qrcode_image = $imageName;
        $barang->save();
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',
    ]);
    }

    public function destroy($id_barang){
        $barang = Barang::find($id_barang);
        if($barang){
            $barang->delete();
        }
        return redirect()->back()->with(['success_message' => 'Data telah terhapus.',]);
    }

    public function exportAlatPerlengkapan(){
        $barang = Barang::with(['jenisbarang'])->where('id_jenis_barang', '!=', 3)->get();
        
        $pdf = PDF::loadView('barang.export.alat', [
            'barang' => $barang,
        ]);

        return $pdf->download('Alat & Perlengkapan Sija.pdf');    
    }
    public function exportBahan(){
        $barang = Barang::with(['jenisbarang'])->where('id_jenis_barang', 3)->get();
        $inventaris = Inventaris::all();
        $totals = $inventaris->groupBy('id_barang')->map(function ($group) {
            return $group->sum('jumlah_barang');
        });
        // dd($alatdanperlengkapan);
          // Create an associative array where keys are id_barang and values are updated stok_barang
        $updatedStokBarang = $barang->mapWithKeys(function ($barangItem) use ($totals) {
            $id_barang = $barangItem->id_barang;
            $total = $totals->get($id_barang, 0); // Get the total or default to 0
            return [$id_barang => $barangItem->stok_barang - $total];
        }); 
        $pdf = PDF::loadView('barang.export.bahan', [
            'barang' => $barang,
            'updatedStokBarang' => $updatedStokBarang,
            'totals' => $totals,
        ]);

        return $pdf->download('Bahan Praktik Sija.pdf');    
    }
}