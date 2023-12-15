<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembelian>
 */
class PembelianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tgl_pembelian' => fake()->date(),
            'nama_toko' => $this->faker->company,
            'total_pembelian' => $this->faker->randomNumber(5),
            'stok_barang' => $this->faker->randomNumber(3),
            'keterangan_anggaran' => $this->faker->sentence,
            'nota_pembelian' =>null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
