<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    
    public static $storePath = 'uploads/stores/';

    protected $table = 'stores';
    protected $fillable = [
        'storename',
        'light_logo',
        'dark_logo',
        'base_url',
        'user_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
