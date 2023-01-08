<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyEmail extends Model
{
    protected $fillable = [
      	'company_id',
    	'email_address'
    ];
}
