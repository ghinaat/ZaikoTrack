<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Imports\KaryawanImport;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
    
        $karyawan = Karyawan::all()->except(1);
    
        return view('karyawan.index',[
            'karyawan' => $karyawan,
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
            'nama_karyawan' => 'required',
        ]);
        $array = $request->only([
            'nama_karyawan',
        ]);
        $karyawan = Karyawan::create($array);

        return redirect()->back()
            ->with('success_message', 'Data telah tersimpan');

    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_karyawan)
    {

        $request->validate([
            'nama_karyawan' => 'required',
        ]);

        $karyawan = Karyawan::find($id_karyawan);
        $karyawan->nama_karyawan = $request->nama_karyawan;
        $karyawan->save();

        return redirect()->back()->with('success_message', 'Data telah tersimpan');
    }


    public function import(Request $request)
    {
        
        Excel::import(new KaryawanImport, $request->file('file')->store('karyawan'));

        return redirect()->back()->with([
            'success_message' => 'Data telah Tersimpan',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        //
    }
}