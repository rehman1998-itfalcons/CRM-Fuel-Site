<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
    	'name',
        'phone_no',
        'address',
        'company_uid',
        'tax_no',
    ];
  
  	public function emails()
    {
		return $this->hasMany(CompanyEmail::class);
    }
  
  	public function sub_companies()
    {
		return $this->hasMany(SubCompany::class,'company_id','id');
    }
}
