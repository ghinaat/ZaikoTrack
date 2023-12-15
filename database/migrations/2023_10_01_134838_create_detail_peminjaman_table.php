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
            $table->date('tgl_kembali');
            $table->integer('jumlah_barang');
            $table->enum('status', ['sudah_dikembalikan', 'belum_dikembalikan']);
            $table->enum('kondisi_barang_akhir', ['lengkap', 'tidak_lengkap', 'rusak']);
            $table->string('ket_tidak_lengkap_awal');
            $table->string('ket_tidak_lengkap_akhir');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
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