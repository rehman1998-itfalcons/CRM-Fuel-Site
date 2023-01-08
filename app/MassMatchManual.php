<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MassMatchManual extends Model
{
    protected $fillable = [
    	'purchases',
      	'sales'
    ];

}
