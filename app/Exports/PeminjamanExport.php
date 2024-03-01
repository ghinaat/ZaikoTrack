<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PeminjamanExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }
    
    public function view(): View
    {
        // dd($this->lemburs['data']);

        return view('peminjaman.export', [
            'peminjaman' => $this->peminjaman,
        ]);
    }
}