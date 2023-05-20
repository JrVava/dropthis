<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleasePlatform extends Model
{
    use HasFactory;
    protected $table = 'release_platforms';
    protected $fillable = [
        'code',
        'url',
        'release_id '
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getStore(){
        return $this->hasMany(Store::class,'storename','code');
    }
}
