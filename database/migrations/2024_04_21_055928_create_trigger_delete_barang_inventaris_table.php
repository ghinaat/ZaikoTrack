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
        CREATE TRIGGER delete_stok_barang_inventaris AFTER DELETE ON inventaris FOR EACH ROW
        BEGIN
            DECLARE jenis_barang_id INT;
            SELECT id_jenis_barang INTO jenis_barang_id FROM barang WHERE id_barang = OLD.id_barang;
            
            IF jenis_barang_id = 3 THEN
                UPDATE barang SET stok_barang = stok_barang + OLD.jumlah_barang WHERE id_barang = OLD.id_barang;
            END IF;
        END
        ');
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delete_stok_barang_inventaris');
    }
};
