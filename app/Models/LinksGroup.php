<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinksGroup extends Model
{
    use HasFactory;

    protected $table = 'links_groups';

    protected $fillable = [
    	'link_id',
    	'group_id',
    	'created_by_id'
    ];

    public function group(){
    	return $this->belongsTo(Group::class)->withDefault();
    }
}
