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
        CREATE TRIGGER update_stok_barang_inventaris AFTER UPDATE ON inventaris
        FOR EACH ROW
        BEGIN
        UPDATE barang 
        SET stok_barang = stok_barang + IFNULL(OLD.jumlah_barang, 0) - IFNULL(NEW.jumlah_barang, 0)         
        WHERE id_barang = NEW.id_barang;
        END
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER "update_stok_barang_inventaris"');
    }
};
