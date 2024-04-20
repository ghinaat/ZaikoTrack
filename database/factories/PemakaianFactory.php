<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemakaian>
 */
class PemakaianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pilih dua dari tiga properti untuk diisi dengan '1'
        $randomProperties = $this->faker->randomElements(['id_users', 'id_karyawan', 'id_guru'], 2);
    
        // Tentukan nilai untuk masing-masing properti
        $idSiswa = in_array('id_users', $randomProperties) ? 1 : $this->faker->numberBetween(4, 6);
        $idKaryawan = in_array('id_karyawan', $randomProperties) ? 1 : $this->faker->numberBetween(2, 10);
        $idGuru = in_array('id_guru', $randomProperties) ? 1 : $this->faker->numberBetween(2, 10);
        $jurusan = $this->faker->randomElement(['SIJA', 'TKJ', 'MM', 'RPL']);

        // Tentukan status berdasarkan nilai-nilai properti yang telah ditentukan
        if (in_array('id_users', $randomProperties)) {
            $status = 'siswa';
        } elseif (in_array('id_karyawan', $randomProperties)) {
            $status = 'karyawan';
        } elseif (in_array('id_guru', $randomProperties)) {
            $status = 'guru';
        } 

        return [
            'id_users' => $idSiswa,
            'id_karyawan' => $idKaryawan,
            'id_guru' => $idGuru,
            'status' => $status,
            'kelas' => $this->faker->numberBetween(10, 13),
            'jurusan' => $jurusan,
            'tgl_pakai' => $this->faker->dateTimeBetween(date('Y') . '-01-01', 'now'),
            'keterangan_pemakaian' => null,
            // Tambahkan atribut lain sesuai kebutuhan
        ];
    }
    
}