<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'image'
    ];

    public function user()
        {
            return $this->belongsTo('app\Models\User.php','user_id','id');
        }
}
