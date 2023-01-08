<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartAccount extends Model
{
    protected $fillable=[
        'title',
        'description',
        'code',
        'account_type'
    ];
  
  	public function chartAccountType()
    {
    	return $this->belongsTo(ChartAccountType::class,'account_type');
    }
  
  public function subaccounts()
    {
    	return $this->hasMany(SubAccount::class);
    }
   public function bankaccounts()
    {
    	return $this->hasMany(Account::class,'chart_account_id');
    }
}
