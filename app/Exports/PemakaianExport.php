<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PemakaianExport implements FromView
{
    protected $pemakaians;
    protected $dataDetail;
    protected $start_date;
    protected $end_date;

    public function setPemakaians($pemakaians)
    {
        $this->pemakaians = $pemakaians;

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
        return view('pemakaian.export', [
            'pemakaians' => $this->pemakaians,
            'dataDetail' => $this->dataDetail,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }
}