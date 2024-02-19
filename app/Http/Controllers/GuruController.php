<?php

namespace App\Http\Controllers;

use App\Imports\GuruImport;
use App\Models\Guru;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function import(Request $request){
        Excel::import(new GuruImport, $request->file('file')->store('guru'));

        return redirect()->back()->with([
            'success_message' => 'Data telah Tersimpan',
        ]);
    }

    public function index()
    {
        $guru = Guru::all()->except('1');
        return view ('guru.index', [
            'guru' => $guru,
        ]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'nama_guru' => 'required'
        ]);
        
        $guru = new Guru();
        $guru->nip = $request->nip;
        $guru->nama_guru = $request->nama_guru;
        $guru->save();
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_guru)
    {
        $request->validate([
            'nip' => 'required',
            'nama_guru' => 'required'
        ]);
        
        $guru = Guru::find($id_guru);
        $guru->nip = $request->nip;
        $guru->nama_guru = $request->nama_guru;
        $guru->save();
        return redirect()->back()->with(['success_message' => 'Data telah tersimpan.',]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_guru)
    {
        $guru = Guru::find($id_guru);
        if ($guru) {
            $guru->delete();
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }
}