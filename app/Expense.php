<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
      	'chart_account_id',
      	'sub_account_id',
      	'amount',
      	'datetime',
      	'ref_no',
      	'payee',
      	'description',
      	'attachment',
    ];
  
  	public function chartAccount()
    {
    	return $this->belongsTo(ChartAccount::class,'chart_account_id');
    }
  
  public function subaccount()
    {
    	return $this->belongsTo(SubAccount::class,'sub_account_id');
    }
}
