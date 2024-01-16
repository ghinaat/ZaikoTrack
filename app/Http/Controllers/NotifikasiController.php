<?php

namespace App\Http\Controllers;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = auth()->user()->notifikasi()->orderBy('created_at', 'desc')->get();
    
        return view('notifikasi.index', [
            'notifikasis' => $notifikasi,
        ]);
    }

    public function detail(Request $request, $id_notifikasi)
    {
        $notifikasi = Notifikasi::find($id_notifikasi);

        if ($notifikasi->id_users != auth()->user()->id_users) {
            return abort(403);
        }   

        $notifikasi->is_dibaca = 'dibaca';

        $notifikasi->update();

        return view('notifikasi.detail', [
            'notifikasi' => $notifikasi,
        ]);
    }
}