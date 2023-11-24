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
    protected $date = ['tgl_pakai'];
}