<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignTracks extends Model
{
    use HasFactory;

    protected $table = 'campaign_tracks';

    protected $fillable = [
        'campaign_id',
        'track',
        'track_genre',
        'mp3_audio',
        'wav_audio'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    public static $wavAudio = 'uploads/campaign/wavAudio';
    public static $mp3Audio = 'uploads/campaign/mp3Audio';

}
