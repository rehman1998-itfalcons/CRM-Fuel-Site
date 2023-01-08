<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCompanyEmail extends Model
{
    protected $fillable = [
      	'sub_company_id',
    	'email_address'
    ];
}