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
        DB::unprepared('
       CREATE TRIGGER Delete_STOK AFTER DELETE on detail_peminjaman FOR EACH ROW
            BEGIN
                UPDATE inventaris SET jumlah_barang = jumlah_barang + old.Jumlah_barang
                WHERE id_inventaris = old.id_inventaris;
            END
       ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_delete_stok_inventaris');
    }
};