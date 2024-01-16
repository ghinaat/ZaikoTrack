<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pemakaian', function (Blueprint $table) {
            $table->increments('id_detail_pemakaian');
<<<<<<< HEAD:database/migrations/2023_10_01_134850_create_detail_pemakaian_table.php
            $table->unsignedInteger('id_pemakaian');
            $table->unsignedInteger('id_inventaris');
            $table->integer('jumlah_barang');
            $table->foreign('id_pemakaian')->references('id_pemakaian')->on('pemakaian')->onDelete('cascade');
            $table->foreign('id_inventaris')->references('id_inventaris')->on('inventaris')->onDelete('cascade');
=======
          
>>>>>>> d15f84c94a8bd33eb7e394ba7f6900a27e3e2a57:database/migrations/2024_01_15_041134_create_detail_pemakaian_table.php
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemakaian');
    }
};