<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable=[
        'user_id',
        'ip_address',
        'browser_agent',
        'country_name',
        'country_code',
        'region_name',
        'city_name',
        'zip_code',
        'latitude',
        'longitude',
        'area_code'
    ];
  
  	public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}