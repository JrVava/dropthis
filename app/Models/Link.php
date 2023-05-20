<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    
    protected $table = 'links';
    
    protected $fillable = [
        'id',
        'slug',
        'name',
        'url',
        'created_by_id',
        'updated_by_id '
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function linksGroup(){
    	return $this->hasMany(LinksGroup::class);
    }
    public function dateFilterLinksGroup(){
        return $this->hasMany(LinksGroup::class)->whereMonth('created_at', '=', date('m'));
    }

    public function click(){
        return $this->hasMany(Click::class,'links_id','id')->orderBy('created_at', 'desc');
    }

    public function dateFilterClick(){
        return $this->hasMany(Click::class,'links_id','id')->orderBy('created_at', 'desc')->whereMonth('created_at', '=', date('m'));
    }  
}
