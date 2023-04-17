<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'page_id', 'image', 'state','created_at', 'updated_at'
    ];

}
