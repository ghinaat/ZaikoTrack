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
            CREATE TRIGGER Delete_stok_barang AFTER DELETE on detail_pembelian FOR EACH ROW
                BEGIN
                    UPDATE barang SET stok_barang = stok_barang - old.jumlah_barang 
                    WHERE id_detail_pembelian = old.id_detail_pembelian;
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER "Update_stok_barang"');
    }
};