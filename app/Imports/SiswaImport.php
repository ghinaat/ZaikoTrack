<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2; // Start reading data from row 2 (skipping headers in row 1)
    }

    public function model(array $row)
    {
        $nis = $row[0];
        $nama_siswa = $row[1];

        if (empty($nama_siswa)) {
            return null; // Or handle the empty value as needed, like skipping the User creation
        }

       

        return new User([
            'nis' => $kode_finger,
            'nama_siswa' => $nama_pegawai,

        ]);
    }
}