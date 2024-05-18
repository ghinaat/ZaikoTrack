<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BahanExport implements FromView
{
    protected $bahans;
    
    public function setBahan($bahans)
    {
        $this->bahans = $bahans;

        return $this;
    }

    public function view(): View
    {
        return view('barang.export.bahan', [
            'bahans' => $this->bahans,
        ]);
    }
}