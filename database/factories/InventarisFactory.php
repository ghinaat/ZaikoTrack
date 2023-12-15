<?php

namespace Database\Factories;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventaris>
 */
class InventarisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_barang' => $this->faker->numberBetween(1, 3),
            'id_ruangan' => $this->faker->numberBetween(1, 3),
            'jumlah_barang' =>  $this->faker->numberBetween(1, 100),
            'kondisi_barang' =>  $this->faker->randomElement(['lengkap', 'tidak_lengkap', 'rusak']),
            'ket_barang' => substr($this->faker->text, 0, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}