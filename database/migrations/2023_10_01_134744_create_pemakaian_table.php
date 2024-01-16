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
        Schema::create('pemakaian', function (Blueprint $table) {
            $table->increments('id_pemakaian');
            $table->string('nama_lengkap', 100);
            $table->string('kelas', 50)->nullable();
            $table->string('jurusan', 50)->nullable();
            $table->date('tgl_pakai');
            $table->string('keterangan_pemakaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaian');
    }
};