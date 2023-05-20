<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use HasFactory;
    protected $table = 'releases';

    public static $releasePath = 'uploads/releases/';

    protected $fillable = [
        'conver',
        'artist',
        'track',
        'lable',
        'release',
        'total_click',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function totalClick(){
        return $this->hasMany(ReleaseClick::class);
    }
    public function platform(){
        
        return $this->hasMany(ReleasePlatform::class)->orderBy('level_order');
    }
}
