<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'plan_id',
        'coupon_id',
        'payment_method',
        'amount',
        'no_of_credits',
        'currency',
        'status',
        'status_reason',
        'transaction_id',
        'paypal_response',
        'paypal_status',

    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        
        'transaction_update_time' => 'datetime',
        'transaction_create_time' => 'datetime'
    ]; 

    public function plan(){
        return $this->hasOne(Plan::class,'id','plan_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
