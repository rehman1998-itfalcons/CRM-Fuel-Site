<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
    	'sender_id',
      	'record_id',
      	'comment',
      	'type',
      	'url',
      	'seen_status'
    ];
  
  	public function user()
    {
		return $this->belongsTo(User::class,'sender_id');
    }
}
