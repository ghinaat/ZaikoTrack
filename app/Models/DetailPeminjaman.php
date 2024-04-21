<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;
    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'id_detail_peminjaman';

    protected $date = ['tgl_pinjam', 'tgl_kembali'];

    protected $guarded = ['id_detail_peminjaman'];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_inventaris', 'id_inventaris');
    }
    public function inventarisis()
    {
        return $this->hasMany(Inventaris::class, 'id_inventaris', 'id_inventaris');
    }
    
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}