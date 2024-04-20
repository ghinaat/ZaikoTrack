<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pemakaian';
    protected $table = 'pemakaian';
    protected $guarded = ['id_pemakaian'];
    protected $dates = ['tgl_pakai']; // Perhatikan penggunaan 'dates' bukan 'date'


    public function detailpemakaian()
    {
    return $this->hasMany(DetailPemakaian::class, 'id_pemakaian', 'id_pemakaian');
    }
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_inventaris', 'id_inventaris');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }


    

  

}