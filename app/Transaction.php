<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    	'account_id',
    	'date',
    	'withdraw',
    	'deposit',
    	'description',
      	'status'
    ];
  
  	public function account()
    {
    	return $this->belongsTo(Account::class,'account_id');
    }
}
