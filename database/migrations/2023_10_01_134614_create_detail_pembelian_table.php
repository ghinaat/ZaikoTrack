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
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->increments('id_detail_pembelian');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_pembelian');
            $table->integer('jumlah_barang');
            $table->integer('subtotal_pembelian');
            $table->integer('harga_perbarang');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};