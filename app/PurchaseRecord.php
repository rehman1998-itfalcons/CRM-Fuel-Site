<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseRecord extends Model
{
    protected $fillable = [
    	'supplier_company_id',
    	'category_id',
    	'invoice_number',
		'invoice_uid',
    	'purchase_no',
    	'datetime',
    	'load_number',
    	'order_number',
    	'gst_status',
      	'status',
		  'myob_status',
      	'match_status',
      	'paid_status',
      	'purchaseinvoices',
      	'total_quantity',
      	'total_amount',
      	'paid_amount',
      	'deleted_status'
    ];

  	public function category()
    {
    	return $this->belongsTo(Category::class, 'category_id');
    }

  	public function fuelCompany()
    {
      return $this->belongsTo(SupplierCompany::class, 'supplier_company_id');
    }

  	public function products()
    {
    	return $this->hasMany(PurchaseRecordProduct::class);
    }

  	public function transactionHistory()
    {
    	return $this->hasMany(TransactionAllocation::class, 'purchase_record_id');
    }
}
