<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';

    protected $primaryKey = 'id_peminjaman';

    protected $date = ['tgl_pinjam', 'tgl_kembali']
    ;
    protected $guarded = ['id_peminjaman'];    

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_inventaris', 'id_inventaris');
    }
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
    
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tgl_pinjam = now();
        });

    }
   
}