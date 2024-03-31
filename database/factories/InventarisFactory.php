<?php

namespace Database\Factories;
use App\Models\Barang;
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
        $barang = Barang::inRandomOrder()->first();
        return [
            'id_barang' => $barang->id_barang,
            'id_ruangan' => $this->faker->numberBetween(1, 3),
            'jumlah_barang' =>  $this->faker->numberBetween(1, 100),
            'kondisi_barang' =>  $this->faker->randomElement(['lengkap', 'tidak_lengkap', 'rusak']),
            'ket_barang' => substr($this->faker->text, 0, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}