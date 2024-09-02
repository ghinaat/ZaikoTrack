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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->increments('id_inventaris');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_ruangan');
            $table->integer('jumlah_barang')->nullable();
            $table->enum('kondisi_barang', ['lengkap', 'tidak_lengkap', 'rusak'])->nullable();
            $table->string('ket_barang', 50)->nullable();
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};