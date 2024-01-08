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
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->increments('id_detail_peminjaman');
            $table->unsignedInteger('id_peminjaman');
            $table->unsignedInteger('id_inventaris');
            $table->integer('jumlah_barang');
            $table->enum('status', ['sudah_dikembalikan', 'dipinjam'])->nullable();
            $table->enum('kondisi_barang_akhir', ['lengkap', 'tidak_lengkap', 'rusak'])->nullable();
            $table->string('ket_tidak_lengkap_awal')->nullable();
            $table->string('ket_tidak_lengkap_akhir')->nullable();
            $table->foreign('id_inventaris')->references('id_inventaris')->on('inventaris')->onDelete('cascade');
            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};