<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordProduct extends Model
{
    protected $fillable = [
    	'record_id',
      	'product_id',
      	'qty',
      	'whole_sale',
      	'discount',
      	'delivery_rate',
      	'brand_charges',
      	'cost_of_credit'
    ];
  
  	public function product()
	{
      return $this->belongsTo(Product::class,'product_id');
    }
}
