<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class PemakaianExport implements FromCollection
{
    protected $pemakaians;
    protected $dataDetail;
    protected $startDate;
    protected $endDate;

    public function __construct($pemakaians, $dataDetail, $startDate = null, $endDate = null)
    {
        $this->pemakaians = $pemakaians;
        $this->dataDetail = $dataDetail;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $exportData = [];

        $exportData[] = ['Rekap Data Pemakaian Barang SIJA'];

        if ($this->startDate && $this->endDate) {
            $exportData[] = ['Tanggal Awal:', $this->startDate, 'Tanggal Akhir:', $this->endDate];
        }

        $exportData[] = ['', '', '', '', '', ''];
        $exportData[] = ['No.', 'Nama', 'Kelas Jurusan', 'Tanggal Pakai', 'Nama Barang', 'Jumlah'];

        $nomorUrut = 1;

        foreach ($this->pemakaians as $pemakaian) {
            foreach ($this->dataDetail as $detail) {
                if (isset($detail['id_pemakaian']) && $detail['id_pemakaian'] == $pemakaian->id_pemakaian) {
                    $exportData[] = [
                        $nomorUrut++,
                        $this->getNamaPemakai($pemakaian),
                        $pemakaian->kelas . ' ' . $pemakaian->jurusan,
                        $pemakaian->tgl_pakai,
                        $detail->inventaris->barang['nama_barang'],
                        $detail['jumlah_barang']
                    ];
                }
            }
        }

        return new Collection($exportData);
    }

    protected function getNamaPemakai($pemakaian)
    {
        if ($pemakaian->id_siswa !== 1) {
            return $pemakaian->siswa->nama_siswa;
        } elseif ($pemakaian->id_guru !== 1) {
            return $pemakaian->guru->nama_guru;
        } elseif ($pemakaian->id_karyawan !== 1) {
            return $pemakaian->karyawan->nama_karyawan;
        }

        return '';
    }
}