<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'reviews_feedbacks';

    protected $fillable = [
        'user_id',
        'campaign_id',
        'name',
        'email',
        'supporting',
        'dj_quote',
        'best_mix',
        'rating',
        'ip'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // public function user(){
    //     return $this->hasOne(User::class, 'id','user_id');
    // }

    public function getCountryName(){
        if($this->country){
            $country = Country::where(['short_code' => $this->country])->first();
            if($country){
                return $country->country;
            }
        }
        return "";
    }
}
