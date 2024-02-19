<?php

namespace Database\Factories;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPeminjaman>
 */
class DetailPeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_peminjaman' => function () {
                return \App\Models\Peminjaman::factory()->create()->id_peminjaman;
            },
            'id_inventaris' => function () {
                return \App\Models\Inventaris::factory()->create()->id_inventaris;
            },
            'jumlah_barang' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['sudah_dikembalikan', 'dipinjam']),
            'kondisi_barang_akhir' => $this->faker->randomElement(['lengkap', 'tidak_lengkap', 'rusak']),
            'ket_tidak_lengkap_awal' => $this->faker->optional()->sentence,
            'ket_tidak_lengkap_akhir' => $this->faker->optional()->sentence,
        ];
    }
}