<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartAccountType extends Model
{
    protected $fillable=[
        'title',
    ];
  
  public function chartAccount()
    {
		return $this->hasMany(ChartAccount::class);
    }
}
