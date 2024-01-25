<?php

namespace App\Http\Controllers;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
    
        $siswa = Siswa::all()->except(1);
    
        return view('siswa.index',[
            'siswa' => $siswa,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required',
            'nis' => 'required',
        ]);
        $array = $request->only([
            'nama_siswa',
            'nis',
        ]);
        $siswa = Siswa::create($array);

        return redirect()->back()
            ->with('success_message', 'Data telah tersimpan');

    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_siswa)
    {

        $request->validate([
            'nama_siswa' => 'required',
            'nis' => 'required',
        ]);

        $siswa = Siswa::find($id_siswa);
        $siswa->nama_siswa = $request->nama_siswa;
        $siswa->nis = $request->nis;
        $siswa->save();

        return redirect()->back()->with('success_message', 'Data telah tersimpan');
    }

    public function import(Request $request)
    {
        
        Excel::import(new SiswaImport, $request->file('file')->store('siswa'));

        return redirect()->back()->with([
            'success_message' => 'Data telah Tersimpan',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_siswa)
    {
        $siswa = Siswa::find($id_siswa);
        if ($siswa) {
            $siswa->delete();
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }
}