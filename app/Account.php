<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable=[
        'chart_account_id',
        'account_name',
        'account_number',
        'opening_date',
        'bank_name',
        'opening_balance',
        'account_type',
        'ac_uid',
        'description',
    ];
  
  	public function chartAccount()
    {
    	return $this->belongsTo(ChartAccount::class, 'chart_account_id');
    }
}
