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

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_inventaris', 'id_inventaris');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id_inventaris', 'id_inventaris');
    }


    

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tgl_pakai = now();
        });
    }

}