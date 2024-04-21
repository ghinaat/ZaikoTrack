<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PeminjamanExport implements FromView
{
    protected $peminjamans;
    protected $id_barang;
    protected $dataDetail;
    protected $start_date;
    protected $end_date;

    public function setPeminjamans($peminjamans)
    {
        $this->peminjamans = $peminjamans;

        return $this;
    }

    
    public function setBarang($id_barang)
    {
        $this->id_barang = $id_barang;

        return $this;
    }

    public function setDataDetail($dataDetail)
    {
        $this->dataDetail = $dataDetail;

        return $this;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function view(): View
    {
        return view('peminjaman.export', [
            'peminjamans' => $this->peminjamans,
            'id_barang' => $this->id_barang,
            'dataDetail' => $this->dataDetail,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }
}