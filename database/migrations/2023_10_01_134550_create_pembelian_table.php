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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian');
            $table->date('tgl_pembelian');
            $table->string('nama_toko', 100);
            $table->integer('total_pembelian');
            $table->integer('stok_barang');
            $table->string('keterangan_anggaran');
            $table->string('nota_pembelian', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};