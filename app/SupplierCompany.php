<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierCompany extends Model
{
    protected $fillable = [
      'name',
      'supplier_uid',
      'display_id',
      'ac_uid',
      'account_id',
    ];
  
  	public function sales()
    {
    	return $this->hasMany(Record::class,'supplier_company_id');  
    }
}
