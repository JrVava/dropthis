<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseClick extends Model
{
    use HasFactory;
    protected $table = 'release_clicks';
    protected $fillable = [
        'release_id',
        'user_id',
        'email',
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

    public function getRelease(){
        $platformUrl = ReleasePlatform::where(['id' => $this->platform_id])->select('url')->first();
        $url = '';
        if(isset($platformUrl->url)){
            $url = $platformUrl->url;
        }else{
            $url = '';
        }
        return $url;
    }
}
