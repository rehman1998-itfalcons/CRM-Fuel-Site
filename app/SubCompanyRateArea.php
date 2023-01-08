<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCompanyRateArea extends Model
{
    protected $fillable = [
      	'sub_company_id',
    	'product_id',
    	'whole_sale',
    	'discount',
    	'delivery_rate',
    	'brand_charges',
    	'cost_of_credit'
    ];
  
  	public function subCompany()
    {
      return $this->belongsTo(SubCompany::class);
    }
  
  	public function product()
    {
      return $this->belongsTo(Product::class);
    }
}