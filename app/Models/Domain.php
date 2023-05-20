<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $table = 'domains';
    
    protected $fillable = [
	    'host',
	    'status',
	    'created_by_id',
	    'updated_by_id '
    ];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime'
	];
}
