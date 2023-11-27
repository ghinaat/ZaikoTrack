<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';
    protected $primaryKey = 'id_detail_pembelian';
    protected $fillable = [
        'id_barang',
        'id_pembelian',
        'jumlah_barang',
        'subtotal_pembelian',
        'harga_perbarang',
    ];

    public function barang(){
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang' );
    }    
}