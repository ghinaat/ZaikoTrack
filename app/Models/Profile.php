<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = 'profile';

    protected $primaryKey = 'id_profile';

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    protected $guarded = ['id_profile'];
}
