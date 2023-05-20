<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'no_of_credits',
        'start_date',
        'expiry_date',
        'one_time_use',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ]; 

    public function userCoupon(){
        return $this->hasMany(UserCoupon::class);
    }
}
