<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadMapping extends Model
{
    use HasFactory;

    protected $table = 'download_mapping';

    protected $fillable = [
        'campaign_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getUser(){
        $user = User::where('id','=',$this->user_id)->first();
        return $user->name;
    }

    public function getEmailGroup(){
        $emailGroup = EmailGroup::where('email','=',$this->email)->first();
        return $emailGroup->artist;
    }
}
