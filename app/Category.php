<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    protected $fillable = [
    	'name',
      	'rate_whole_sale',
      	'whole_sale_display',
      	'rate_discount',
      	'rate_delivery_rate',
      	'rate_brand_charges',
      	'rate_cost_of_credit',
      	'report_whole_sale',
      	'report_discount',
      	'report_delivery_rate',
      	'report_brand_charges',
      	'report_cost_of_credit',
      	'invoice_whole_sale',
      	'invoice_discount',
      	'invoice_delivery_rate',
      	'invoice_brand_charges',
      	'invoice_cost_of_credit',
      	'status'
    ];
  
}
