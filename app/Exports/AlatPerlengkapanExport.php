<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AlatPerlengkapanExport implements FromView
{
    protected $barangs;
    
    public function setAlatPerlengkapan($barangs)
    {
        $this->barangs = $barangs;

        return $this;
    }

    public function view(): View
    {
        return view('barang.export.alat', [
            'barangs' => $this->barangs,
        ]);
    }
}