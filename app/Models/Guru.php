<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_guru';
    protected $table = 'guru';
    protected $guarded = ['id_guru'];
    
}