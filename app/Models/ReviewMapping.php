<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewMapping extends Model
{
    use HasFactory;
    protected $table = 'review_mapping';

    protected $fillable = [
        'user_id',
        'campaign_id',
        'can_cant_submit_feedbacks'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
