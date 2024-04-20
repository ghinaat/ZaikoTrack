<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifikasi';

    protected $primaryKey = 'id_notifikasi';

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    protected $guarded = ['id_notifikasi'];
}