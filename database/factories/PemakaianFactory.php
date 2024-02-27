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
        $randomProperties = $this->faker->randomElements(['id_siswa', 'id_karyawan', 'id_guru'], 2);
    
        // Tentukan nilai untuk masing-masing properti
        $idSiswa = in_array('id_siswa', $randomProperties) ? 1 : $this->faker->numberBetween(2, 10);
        $idKaryawan = in_array('id_karyawan', $randomProperties) ? 1 : $this->faker->numberBetween(2, 10);
        $idGuru = in_array('id_guru', $randomProperties) ? 1 : $this->faker->numberBetween(2, 10);
        $jurusan = $this->faker->randomElement(['SIJA', 'TKJ', 'MM', 'RPL']);
        $status = in_array('id_siswa', $randomProperties) ? 'siswa' : (in_array('id_karyawan', $randomProperties) ? 'karyawan' : 'guru');
        return [
            'id_siswa' => $idSiswa,
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