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
        CREATE TRIGGER update_stok_barang_inventaris AFTER UPDATE  ON inventaris FOR EACH ROW
        BEGIN
            DECLARE jenis_barang_id INT;
            SELECT id_jenis_barang INTO jenis_barang_id FROM barang WHERE id_barang = NEW.id_barang;
            
            IF jenis_barang_id = 3 THEN
                UPDATE barang SET stok_barang =  stok_barang - old.jumlah_barang + new.jumlah_barang WHERE id_barang = NEW.id_barang;
            END IF;
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
