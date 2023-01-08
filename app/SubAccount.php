<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    protected $fillable=[
        'chart_account_id',
        'title',
        'description',
        'code'  
    ];
  
   public function chartAccount()
    {
    	return $this->belongsTo(ChartAccount::class,'chart_account_id');
    }
}
