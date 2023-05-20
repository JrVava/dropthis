<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';

    protected $fillable = [
        'label',
        'website',
        'release_number',
        'cover_artwork',
        'description',
        'leave_rating_and_comment',
        'release_date',
        'promo_sendout',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public static $campaignPath = 'uploads/campaigns/';

    public function getTrack(){
        return $this->hasMany(CampaignTracks::class);
    }

    public function getEmailGroup(){
        // $emailGroups = EmailGroup::where(['group' => $this->email_group])->get();
        // return $emailGroups;
        return $this->hasMany(EmailGroup::class,'group','email_group');
    }

    public function userDetails(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
