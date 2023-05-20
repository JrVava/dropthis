<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelSetting extends Model
{
    use HasFactory;
    protected $table = 'label_settings';

    protected $fillable = [
        'label_name',
        'url',
        'email_address',
        'full_company_address',
        'light_version_logo',
        'dark_version_logo',
        'backgroung_image',
        'theme_mode',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public static $labelSettingPath = 'uploads/label-setting/';
}
