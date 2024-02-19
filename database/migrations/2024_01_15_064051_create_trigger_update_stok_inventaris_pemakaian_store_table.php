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
        CREATE TRIGGER Pemakaian_Store AFTER INSERT on DETAIL_PEMAKAIAN FOR EACH ROW
            BEGIN
                UPDATE inventaris SET jumlah_barang = jumlah_barang - NEW.jumlah_barang
                WHERE id_inventaris = new.id_inventaris;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triger_update_stok_inventaris_pemakaian');
    }
};