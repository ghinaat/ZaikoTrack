<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_karyawan';
    protected $table = 'karyawan';
    protected $guarded = ['id_karyawan'];
   
}