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
            CREATE TRIGGER Store_stok_barang AFTER INSERT on detail_pembelian FOR EACH ROW
                BEGIN
                    UPDATE barang SET stok_barang = stok_barang + new.jumlah_barang
                    WHERE id_barang = new.id_barang;
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER "Store_stok_barang"');
    }
};