<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';

    protected $primaryKey = 'id_peminjaman';

    protected $date = ['tgl_pinjam']
    ;

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_inventaris', 'id_inventaris');
    }
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }

    protected $guarded = ['id_peminjaman'];
}