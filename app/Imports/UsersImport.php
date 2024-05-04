<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Profile;
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
        $name = $row[0];
        $nis = $row[1];
        $kelas = $row[2];
        $jurusan = $row[3];
    
        if (empty($name) || empty($nis) || empty($kelas) || empty($jurusan)) {
            return null; // Skip the row if name or NIS is empty
        }
    
        // Use the email from the imported data if available, otherwise create a unique one
        $email = Str::slug($name) . '@cibinong.com';
    
        $profile = Profile::where('nis', $nis)->first();

        // If the profile doesn't exist, create a new one
        if (!$profile) {
            $profile = new Profile();
            // Assign the id_users field from the profile table to the User
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'level' => 'siswa',
            ]);
            $profile->id_users = $user->id_users; // Set the id_users field from the profile table
            $profile->nis = $nis; // Set the NIS
        }

        // Set or update the profile attributes
        $profile->kelas = $kelas;
        $profile->jurusan = $jurusan;

        // Save the profile
        $profile->save();

        return null; // Since we're only updating the Profile, return null to skip creating a User
    }
    
}