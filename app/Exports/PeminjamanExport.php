<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class PeminjamanExport implements FromView
{
    protected $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function view(): View
    {
        dd($this->peminjaman['peminjamanData']);

        return view('peminjaman.index', [
            'peminjaman' => $this->peminjamanData,
        ]);
    }
}