<?php

namespace App\Http\Controllers;

use App\Models\DetailPemakaian;
use App\Models\Guru;
use App\Models\Inventaris;
use App\Models\Karyawan;
use App\Models\Pemakaian;
use App\Models\Siswa;
use App\Exports\PemakaianExport;
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
            $pemakaians = Pemakaian::with(['siswa', 'guru', 'karyawan'])
                ->whereBetween('tgl_pakai', [$start_date, $end_date])
                ->orderBy('id_siswa')
                ->orderBy('id_guru')
                ->orderBy('id_karyawan')
                ->orderBy('tgl_pakai')
                ->get();
        } else {
            $pemakaians = Pemakaian::with(['siswa', 'guru', 'karyawan'])
                ->orderBy('id_siswa')
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
    

    public function index(){
        $groupedPemakaians = Pemakaian::all();
        $siswa = Siswa::all()->except(1);
        $guru = Guru::all()->except(1);
        $karyawan = Karyawan::all()->except(1);
        // dd($groupedPemakaians);
        return view('pemakaian.index',[
            'groupedPemakaians' => $groupedPemakaians,
            'siswa' => $siswa,
            'guru' => $guru,
            'karyawan' => $karyawan,
            
        ]);
    }

    public function showDetail($id_pemakaian){
        $pemakaian = Pemakaian::with('siswa', 'guru', 'karyawan')->find($id_pemakaian);
        $detailPemakaians = DetailPemakaian::with(['inventaris.barang'])->where('id_pemakaian', $pemakaian->id_pemakaian)->get();
        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();
            

        return view('pemakaian.show',[
            'detailpemakaian' => $detailPemakaians,
            'pemakaian' => $pemakaian,
            'barang' => $bahanPraktik,
            
        ]);
    }

    public function create(){
        $idJenisBarang = 3;
        $bahanPraktik = Inventaris::whereHas('barang', function ($query) use ($idJenisBarang) {
            $query->where('id_jenis_barang', $idJenisBarang);})->select('id_barang', DB::raw('MAX(id_inventaris) as max_id_inventaris'))
            ->groupBy('id_barang')->with(['barang'])->get();
            
        $siswa = Siswa::all()->except('1');
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
        ->select('id_ruangan') 
        ->distinct()
        ->with(['ruangan:id_ruangan,nama_ruangan']) // Specify the columns you want
        ->get();
        // Kembalikan data dalam format JSON
        return response()->json($ruanganOptions);
    }

    public function getPemakaianData($id_pemakaian)
    {
        
        $getdatapemakaian = Pemakaian::where('id_pemakaian', $id_pemakaian)->first();

        // dd($getdatapemakaian);

        return response()->json([
            'id_siswa' => $getdatapemakaian->id_siswa,    // Ganti dengan nilai sesuai kebutuhan
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
        $detailPemakaian = new DetailPemakaian();
        $detailPemakaian->id_pemakaian = $request->id_pemakaian;
        $detailPemakaian->id_inventaris = $id_inventaris->id_inventaris;
        $detailPemakaian->jumlah_barang = $request->jumlah_barang;
        $detailPemakaian->jumlah_barang = $request->jumlah_barang;
        $detailPemakaian->save();
        
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
            'id_siswa' => 'nullable',
            'id_guru' => 'nullable',
            'id_karyawan' => 'nullable',
            'status' => 'required',
            'kelas' => 'nullable',
            'jurusan' => 'nullable',
            'keterangan_pemakaian' => 'nullable'

        ]);
        // $id_siswa = $request->filled('id_siswa') ? $request->id_siswa : 1;
        // $id_karyawan = $request->filled('id_karyawan') ? $request->id_karyawan : 1;
        // $id_guru = $request->filled('id_guru') ? $request->id_guru : 1;

        if($request->status == 'siswa'){
            $id_siswa = $request->id_siswa;
            $id_karyawan = 1;
            $id_guru = 1;
        }elseif($request->status == 'guru'){
            $id_siswa = 1;
            $id_karyawan = 1;
            $id_guru = $request->id_guru;
        }else{
            $id_siswa = 1;
            $id_karyawan = $request->id_karyawan;
            $id_guru = 1;
        }

            $pemakaian = new Pemakaian();
            $pemakaian->tgl_pakai = $request->tgl_pakai;
            $pemakaian->id_siswa = $id_siswa;
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
            'id_siswa' => 'nullable',
            'id_guru' => 'nullable',
            'id_karyawan' => 'nullable',
            'kelas' => 'nullable',
            'jurusan' => 'nullable',
            'keterangan_pemakaian' => 'nullable'

        ]);
        $pemakaian = Pemakaian::find($request->id_pemakaian);

        if ($pemakaian) {
            // Data ditemukan
            if($request->status == 'siswa'){
                $id_siswa = $request->id_siswa;
                $id_karyawan = 1;
                $id_guru = 1;
            }elseif($request->status == 'guru'){
                $id_siswa = 1;
                $id_karyawan = 1;
                $id_guru = $request->id_guru;
            }else{
                $id_siswa = 1;
                $id_karyawan = $request->id_karyawan;
                $id_guru = 1;
            }

            // Update data berdasarkan perubahan pada request
            $pemakaian->tgl_pakai = $request->tgl_pakai;
            $pemakaian->id_siswa = $id_siswa;
            $pemakaian->id_guru = $id_karyawan;
            $pemakaian->id_karyawan = $id_guru;
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