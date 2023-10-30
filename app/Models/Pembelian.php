<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $fillable = [
        'tgl_pembelian',
        'nama_toko',
        'total_pembelian',
        'stok_barang',
        'keterangan_anggaran',
        'nota_pembelian',
    ];
}
