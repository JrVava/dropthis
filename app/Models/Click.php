<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    protected $table = 'click';

    protected $fillable = [
        'links_id',
        'uri',
        'host',
        'referer',
        'is_first_click',
        'is_robot',
        'user_agent',
        'os',
        'device',
        'browser_type',
        'browser_veersion',
        'visitor_id',
        'country',
        'ip'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];  
    public function getCountryName(){
        if($this->country){
            $country = Country::where(['short_code' => $this->country])->first();
            if($country){
                return $country->country;
            }
        }
        return "";
    }

    public function getLink(){
        $links = Link::where(['id' => $this->links_id])->first();
        if(isset($links->name)){
            return $links->name;
        }
        return "";
    }

    public function link(){
        return $this->hasMany(Link::class,'id','links_id');
    }
}
