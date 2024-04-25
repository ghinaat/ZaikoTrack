<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPemakaian;
use App\Models\Guru;
use App\Models\Inventaris;
use App\Models\Karyawan;
use App\Models\Pemakaian;
use App\Models\Siswa;
use App\Exports\PemakaianExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PemakaianController extends Controller
{
    public function export(Request $request)
    {
        $defaultStartDate = '2023-01-01';
        $defaultEndDate = '2023-12-31';
    
        $start_date = $request->input('start_date', $defaultStartDate);
        $end_date = $request->input('end_date', $defaultEndDate);
    
        // Cek apakah start_date dan end_date diberikan atau tidak
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pemakaians = Pemakaian::with(['users', 'guru', 'karyawan'])
                ->whereBetween('tgl_pakai', [$start_date, $end_date])
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pakai')
                ->get();
        } else {
            $pemakaians = Pemakaian::with(['siswa', 'guru', 'karyawan'])
                ->orderBy('id_users')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pakai')
                ->get();
        }
    
        $dataDetail = DetailPemakaian::with(['inventaris.barang'])
            ->whereIn('id_pemakaian', $pemakaians->pluck('id_pemakaian'))
            ->get();
    
        return Excel::download((new PemakaianExport)
            ->setPemakaians($pemakaians)
            ->setDataDetail($dataDetail)
            ->setStartDate($start_date)
            ->setEndDate($end_date), 'pemakaian.xlsx');
    }
    

    public function index(Request $request)
    {
        $id_barang = $request->input('id_barang');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $pemakaianFilter = Pemakaian::query();

        // Filter berdasarkan id_barang dan rentang tanggal jika keduanya tersedia
        if ($id_barang && $start_date && $end_date) {
            $pemakaianFilter->whereBetween('tgl_pakai', [$start_date, $end_date])
                            ->whereHas('detailpemakaian.inventaris.barang', function ($query) use ($id_barang) {
                                $query->where('id_barang', $id_barang);
                            });
        } elseif ($id_barang) {
            // Filter hanya berdasarkan id_barang jika id_barang tersedia
            $pemakaianFilter->whereHas('detailpemakaian.inventaris.barang', function ($query) use ($id_barang) {
                $query->where('id_barang', $id_barang);
            });
        } elseif ($start_date && $end_date) {
            // Filter hanya berdasarkan rentang tanggal jika rentang tanggal tersedia
            $pemakaianFilter->whereBetween('tgl_pakai', [$start_date, $end_date]);
        }

        // Menyimpan nilai id_barang ke dalam sesi
        $request->session()->put('selected_id_barang', $id_barang);

        // Mendapatkan data pemakaian yang telah difilter
        $groupedPemakaians = $pemakaianFilter->orderBy('id_pemakaian', 'desc')->get();

        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();
        $siswa = User::where('level', 'siswa')->whereNotIn('id_users', [1])->get();
        $guru = Guru::all()->except(1);
        $karyawan = Karyawan::all()->except(1);
        return view('pemakaian.index',[
            'groupedPemakaians' => $groupedPemakaians,
            'siswa' => $siswa,
            'guru' => $guru,
            'karyawan' => $karyawan,
            'barang' => $bahanPraktik,
            
        ]);
    }

    public function showDetail($id_pemakaian){
        $pemakaian = Pemakaian::with('users', 'guru', 'karyawan')->find($id_pemakaian);
        $detailPemakaians = DetailPemakaian::with(['inventaris.barang'])->where('id_pemakaian', $pemakaian->id_pemakaian)->get();
        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();

            
                    // Mengumpulkan semua id_inventaris dari $dp ke dalam array
        $id_inventaris_list = $detailPemakaians->pluck('id_inventaris');

        // Query untuk mendapatkan id_barang yang sesuai dengan setiap id_inventaris
        $id_barang_old_list = Inventaris::whereIn('id_inventaris', $id_inventaris_list)->pluck('id_barang', 'id_inventaris');

        // Membuat array yang berisi pasangan id_inventaris dan id_barang
        $idBarangOld = $id_barang_old_list->toArray();

        // dd($detailPemakaians);
        return view('pemakaian.show',[
            'detailpemakaian' => $detailPemakaians,
            'pemakaian' => $pemakaian,
            'barang' => $bahanPraktik,
            'idBarangOld' => $idBarangOld,
            
        ]);
    }

    public function create(){
        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();
            
        $siswa = User::where('level', 'siswa')->whereNotIn('id_users', [1])->get();
        $guru = Guru::all()->except('1');
        $karyawan = Karyawan::all()->except('1');   

        return view('pemakaian.create',[
            'barang' => $bahanPraktik,
            'siswa' => $siswa,
            'guru' => $guru,
            'karyawan' => $karyawan,
        ]);
    }

    // app/Http/Controllers/YourController.php
    public function getRuanganOptions($id_barang)
    {
        
        $ruanganOptions = Inventaris::where('id_barang', $id_barang)
        ->select('id_ruangan', 'jumlah_barang') 
        ->distinct()
        ->with(['ruangan:id_ruangan,nama_ruangan']) // Specify the columns you want
        ->get();
        
        // Kembalikan data dalam format JSON
        return response()->json($ruanganOptions);
    }


    public function getStokOptions($id_ruangan)
    {
        // Ambil stok berdasarkan ID ruangan
        $stok = Inventaris::where('id_ruangan', $id_ruangan)->sum('jumlah_barang');
    
        // Kembalikan stok dalam format JSON
        return response()->json(['stok' => $stok]);
    }    
    
    public function getRuanganAndStok($id_detail_pemakaian)
    {

        $DetailOld = DetailPemakaian::with('inventaris')->find($id_detail_pemakaian);
        $id_ruangan = $DetailOld->inventaris->first()->id_ruangan;

        return response()->json([
            'DetailOld' => $DetailOld,
            'id_ruangan'  =>$id_ruangan,
        ]);
    }

    public function getPemakaianData($id_pemakaian)
    {
        
        $getdatapemakaian = Pemakaian::where('id_pemakaian', $id_pemakaian)->first();

        // dd($getdatapemakaian);

        return response()->json([
            'id_users' => $getdatapemakaian->id_users,    // Ganti dengan nilai sesuai kebutuhan
            'id_guru' => $getdatapemakaian->id_guru,    // Ganti dengan nilai sesuai kebutuhan
            'id_karyawan' => $getdatapemakaian->id_karyawan, // Ganti dengan nilai sesuai kebutuhan
            'kelas' => $getdatapemakaian->kelas,             // Ganti dengan nilai sesuai kebutuhan
            'jurusan' => $getdatapemakaian->jurusan,             // Ganti dengan nilai sesuai kebutuhan
            'tgl_pakai' => $getdatapemakaian->tgl_pakai,             // Ganti dengan nilai sesuai kebutuhan
            'keterangan_pemakaian' => $getdatapemakaian->keterangan_pemakaian,             // Ganti dengan nilai sesuai kebutuhan
        ]);
    }


    public function storeDetail(Request $request){
        // dd($request);
        $request->validate([
            'id_barang' => 'required',
            'id_ruangan' => 'required',
            'jumlah_barang' => 'required',
        ]);
        $id_inventaris = Inventaris::where('id_barang', $request->id_barang)->where('id_ruangan', $request->id_ruangan)->with(['barang'])->first();
        
      
        if (!$id_inventaris || $id_inventaris->jumlah_barang < $request->jumlah_barang || $id_inventaris->jumlah_barang - $request->jumlah_barang < 0) {
            return redirect()->back()->with(['error' => 'Stok barang tidak mencukupi.']);
        }

        $detailPemakaian = new DetailPemakaian();
        $detailPemakaian->id_pemakaian = $request->id_pemakaian;
        $detailPemakaian->id_inventaris = $id_inventaris->id_inventaris;
        $detailPemakaian->jumlah_barang = $request->jumlah_barang;
        $detailPemakaian->save();

        $barang = Barang::find($request->id_barang);
        $totalQuantity = Inventaris::where('id_barang', $request->id_barang)
                            ->sum('jumlah_barang');
        $TotalStok = $barang->stok_barang + $totalQuantity;
        
        $pengguna = User::where('level', 'teknisi')->get();


        if ($TotalStok < 3) {
            $notifikasi = new Notifikasi();
            $notifikasi->judul = 'Stok Barang';
            $notifikasi->pesan = 'Stok barang ' . $barang->nama_barang . ' kurang dari 3.';
            $notifikasi->is_dibaca = 'tidak_dibaca';
            $notifikasi->send_email = 'yes';
            $notifikasi->label = 'info';
            $notifikasi->link = '/Barang';
            $notifikasi->id_users = $pengguna->id_users; // Assuming $pengguna is defined somewhere
            $notifikasi->save();
        }
        
        if($request->ajax()){
            $namaBarang = Inventaris::with(['barang'])->where('id_inventaris', $detailPemakaian->id_inventaris)->first();
            $namaRuangan = Inventaris::with(['ruangan'])->where('id_inventaris', $detailPemakaian->id_inventaris)->first();
            return response()->json([
                // 'key' => $key,               // Ganti dengan nilai sesuai kebutuhan
                'nama_barang' => $namaBarang->barang->nama_barang,    // Ganti dengan nilai sesuai kebutuhan
                'nama_ruangan' => $namaRuangan->ruangan->nama_ruangan,    // Ganti dengan nilai sesuai kebutuhan
                'jumlah_barang' => $detailPemakaian->jumlah_barang, // Ganti dengan nilai sesuai kebutuhan
                'id_detail_pemakaian' => $detailPemakaian->id_detail_pemakaian             // Ganti dengan nilai sesuai kebutuhan
            ]); 
        }else{
            return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);
        }
        
    }

    public function store(Request $request){
        // dd($request);
        $request->validate([
            'tgl_pakai' => 'required',
            'id_users' => 'nullable',
            'id_guru' => 'nullable',
            'id_karyawan' => 'nullable',
            'status' => 'nullable',
            'kelas' => 'nullable',
            'jurusan' => 'nullable',
            'keterangan_pemakaian' => 'nullable'

        ]);
        // dd($request);
        $id_users = $request->filled('id_users') ? $request->id_users : 1;
        $id_karyawan = $request->filled('id_karyawan') ? $request->id_karyawan : 1;
        $id_guru = $request->filled('id_guru') ? $request->id_guru : 1;

            $pemakaian = new Pemakaian();
            $pemakaian->tgl_pakai = $request->tgl_pakai;
            $pemakaian->id_users = $id_users;
            $pemakaian->id_guru = $id_guru;
            $pemakaian->id_karyawan = $id_karyawan;
            $pemakaian->status = $request->status;
            $pemakaian->kelas = $request->kelas;
            $pemakaian->jurusan = $request->jurusan;
            $pemakaian->keterangan_pemakaian = $request->keterangan_pemakaian;
            $pemakaian->save();
        
            return response()->json([
                'id_pemakaian' => $pemakaian->id_pemakaian,
            ]);
    }
    

    public function update(Request $request){
        // dd($request);
        $request->validate([
            'tgl_pakai' => 'required',
            'id_users' => 'nullable',
            'id_guru' => 'nullable',
            'id_karyawan' => 'nullable',
            'status' => 'nullable',
            'kelas' => 'nullable',
            'jurusan' => 'nullable',
            'keterangan_pemakaian' => 'nullable'

        ]);
        // dd($request);
        $pemakaian = Pemakaian::find($request->id_pemakaian);

        if ($pemakaian) {
            // Data ditemukan
            if($request->status == 'siswa'){
                $id_users = $request->id_users;
                $id_karyawan = 1;
                $id_guru = 1;
            }elseif($request->status == 'guru'){
                $id_users = 1;
                $id_karyawan = 1;
                $id_guru = $request->id_guru;
            }else{
                $id_users = 1;
                $id_karyawan = $request->id_karyawan;
                $id_guru = 1;
            }

            // Update data berdasarkan perubahan pada request
            $pemakaian->tgl_pakai = $request->tgl_pakai;
            $pemakaian->id_users = $id_users;
            $pemakaian->id_guru = $id_guru;
            $pemakaian->id_karyawan = $id_karyawan;
            $pemakaian->status = $request->status;
            $pemakaian->kelas = $request->kelas;
            $pemakaian->jurusan = $request->jurusan;
            $pemakaian->keterangan_pemakaian = $request->keterangan_pemakaian;
            $pemakaian->save();
        }
            if($request->ajax()){
                return response()->json([
                    'id_pemakaian' => $pemakaian->id_pemakaian,]);
            }else{
                return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);
            }
    }

    public function updateDetail(Request $request, $id_detail_pemakaian){
        $request->validate([
            'id_barang' => 'required',
            'id_ruangan' => 'required',
            'jumlah_barang' => 'required',
        ]);
        dd($request);

        $Idinventaris = Inventaris::where('id_barang', $request->id_barang)->where('id_ruangan', $request->id_ruangan)->with(['barang'])->first();
        $detailPemakaian = DetailPemakaian::find($request->id_detail_pemakaian);
        $detailPemakaian->id_pemakaian = $request->id_pemakaian;
        $detailPemakaian->id_inventaris = $Idinventaris->id_inventaris;
        $detailPemakaian->jumlah_barang = $request->jumlah_barang;
        $detailPemakaian->save();
        
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);
        
    }
    public function destroy($id_pemakaian){
        $pemakaian = Pemakaian::find($id_pemakaian);
        if ($pemakaian) {
            $pemakaian->delete();
            $detailpemakaian = DetailPemakaian::where('id_pemakaian', $pemakaian->id_pemakaian)->first();

            if ($detailpemakaian) {
                return response()->json([
                    'id_detail_pemakaian' => $detailpemakaian->id_pemakaian,
                ]);
            }
        }

        // return redirect('pemakaian')->with(['success_message' => 'Data telah terhapus.',]);

    }

    public function  destroyDetail($id_detail_pemakaian){
        $detailpemakaian = DetailPemakaian::find($id_detail_pemakaian);
        
        if ($detailpemakaian) {
            $detailpemakaian->delete();
        }


    }
}