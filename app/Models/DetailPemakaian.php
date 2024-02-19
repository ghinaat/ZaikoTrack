<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemakaian extends Model
{
    use HasFactory;
    protected $table = 'detail_pemakaian';
    protected $primaryKey = 'id_detail_pemakaian';
    protected $guarded = ['id_detail_pemakaian'];

    public function Pemakaian(){
        return $this->belongsTo(Pemakaian::class, 'id_pemakaian', 'id_pemakaian' );
    }    
    public function Inventaris(){
        return $this->belongsTo(Inventaris::class, 'id_inventaris', 'id_inventaris' );
    }    
}