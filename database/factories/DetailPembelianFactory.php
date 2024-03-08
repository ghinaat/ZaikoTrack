<?php

namespace Database\Factories;

use App\Models\JenisBarang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPembelian>
 */
class DetailPembelianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'id_barang' => $this->faker->numberBetween(1, 3), // Menggunakan $this->faker
            'id_pembelian' => $this->faker->numberBetween(1, 10), // Menggunakan $this->faker
            'jumlah_barang' => $this->faker->randomNumber(2),
            'subtotal_pembelian' => $this->faker->randomNumber(4),
            'harga_perbarang' => $this->faker->randomNumber(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}