<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KaryawanImport implements ToModel, WithStartRow
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
    
        $nama_karyawan = $row[1];

        if (empty($nama_karyawan)) {
            return null; // Or handle the empty value as needed, like skipping the User creation
        }

        return new Karyawan([
        
            'nama_karyawan' => $nama_karyawan,

        ]);
    }
}