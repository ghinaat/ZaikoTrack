<?php

namespace Database\Factories;

use App\Models\DetailPemakaian;
use App\Models\Inventaris;
use App\Models\Pemakaian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPemakaian>
 */
class DetailPemakaianFactory extends Factory
{
    protected $model = DetailPemakaian::class;

    public function definition()
    {
        $pemakaian = Pemakaian::inRandomOrder()->first();
        $inventaris = Inventaris::inRandomOrder()->first();

        return [
            'id_pemakaian' => $pemakaian->id_pemakaian,
            'id_inventaris' => $inventaris->id_inventaris,
            'jumlah_barang' => $this->faker->numberBetween(1, 10),
            // tambahkan atribut lain sesuai kebutuhan
        ];
    }
}