<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Start reading data from row 2 (skipping headers in row 1)
    }

    public function model(array $row)
    {
        $name = $row[1];
        $nis = $row[2];

        if (empty($name)) {
            return null; // Or handle the empty value as needed, like skipping the User creation
        }

        // Use the email from the imported data if available, otherwise create a unique one
        $email = Str::slug($name).'@cibinong.com';

        return new User([
            'name' => $name,
            'email' => $email,
            'nis' => $nis,
            'password' => Hash::make('12345678'),
            'level' => 'siswa',

        ]);
    }
}