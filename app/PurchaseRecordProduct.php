<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseRecordProduct extends Model
{
    protected $fillable = [
    	'purchase_record_id',
    	'product_id',
    	'qty',
    	'rate',
    	'gst',
    	'sub_amount',
    	'gst_amount',
    	'total_amount',
    ];
  
  	public function product()
    {
    	return $this->belongsTo(Product::class);
    }
  
}