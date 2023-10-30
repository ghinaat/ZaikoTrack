<?php

namespace App\Http\Controllers;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::all();

        return view('ruangan.index', [
            'ruangan' => $ruangan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required',
        ]);
        $array = $request->only([
            'nama_ruangan',
        ]);
        $ruangan = Ruangan::create($array);

        return redirect()->back()
            ->with('success_message', 'Data telah tersimpan');

    }

    public function update(Request $request, $id_ruangan)
    {

        $request->validate([
            'nama_ruangan' => 'required',
        ]);

        $ruangan = Ruangan::find($id_ruangan);
        $ruangan->nama_ruangan = $request->nama_ruangan;
        $ruangan->save();

        return redirect()->back()->with('success_message', 'Data telah tersimpan');
    }

    public function destroy($id_ruangan)
    {
        $ruangan = Ruangan::find($id_ruangan);
        if ($ruangan) {
            $ruangan->delete();
        }

        return redirect()->back()->with('success_message', 'Data telah terhapus.');
    }
    
}