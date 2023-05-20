<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignClicks extends Model
{
    use HasFactory;

    protected $table = 'campaign_clicks';

    protected $fillable = [
        'campaign_id',
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

    public function getCampaign(){
        $campaign = Campaign::where(['id' => $this->campaign_id])->first();
        if(isset($campaign->label)){
            return $campaign->label;
        }
        return "";
    }

    public function getUser(){
        $user = User::where('id','=',$this->user_id)->first();
        return $user->name;
    }

    public function getEmailGroup(){
        $emailGroup = EmailGroup::where('email','=',$this->email)->first();
        return $emailGroup->artist;
    }

}
