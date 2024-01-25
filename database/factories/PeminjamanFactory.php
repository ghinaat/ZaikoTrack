<?php

namespace Database\Factories;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusOptions = ['siswa', 'guru', 'karyawan'];

        return [
            'id_siswa' => function () {
                return \App\Models\Siswa::factory()->create()->id_siswa;
            },
            'id_guru' => function () {
                return \App\Models\Guru::factory()->create()->id_guru;
            },
            'id_karyawan' => function () {
                return \App\Models\Karyawan::factory()->create()->id_karyawan;
            },
            'kelas' => $this->faker->optional()->randomElement(['10', '11', '12']),
            'jurusan' => $this->faker->optional()->randomElement(['SIJA', 'TKJ', 'MM']),
            'keterangan_peminjaman' => $this->faker->optional()->sentence,
            'status' => $this->faker->randomElement($statusOptions),
            'tgl_pinjam' => $this->faker->date,
            'tgl_kembali' => $this->faker->date,
        ];
    }
}