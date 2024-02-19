<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuruImport implements ToModel, WithStartRow
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
    public function startColumn(): int
    {
        return 1; // Start reading data from row 2 (skipping headers in row 1)
    }

    public function model(array $row)
{
   
    $nip = (int)$row[1];
    $nama_guru = $row[2];

    if (empty($nama_guru)) {
        return null; // Atau tangani nilai yang kosong sesuai kebutuhan Anda
    }

    return new Guru([
        'nip' => $nip,
        'nama_guru' => $nama_guru,
    ]);
}

}