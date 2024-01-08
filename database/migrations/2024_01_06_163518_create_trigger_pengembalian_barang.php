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
        CREATE TRIGGER Update_Jumlah_Barang_Peminjaman
        AFTER UPDATE ON DETAIL_PEMINJAMAN
        FOR EACH ROW
        BEGIN
            IF NEW.status = "sudah_dikembalikan" THEN
                UPDATE inventaris
                SET jumlah_barang = jumlah_barang + OLD.jumlah_barang
                WHERE id_inventaris = NEW.id_inventaris;
            END IF;
        END
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER "Update_Jumlah_Barang_Peminjaman"');
    }
};