<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
  
    protected $fillable = [
    	'user_id',
    	'category_id',
    	'company_id',
    	'sub_company_id',
      	'supplier_company_id',
      	'datetime',
      	'load_number',
      	'order_number',
      	'splitfullload',
      	'total_without_gst',
      	'gst',
      	'gst_status',
      	'split_load_status',
      	'split_load_charges',
      	'split_load_des',
      	'total_amount',
      	'paid_amount',
      	'supervisor_status',
      	'invoice_no',
        'invoice_number',
		'invoice_uid',
      	'delivery_docket',
      	'bill_of_lading',
      	'status',
		  'myob_status',
      	'email_status',
      	'paid_status',
      	'mass_match_status',
      	'deleted_status',
        'cancel_reason',
        'follows_note'
    ];
  
  	public function user()
    {
		return $this->belongsTo(User::class,'user_id');
    }
  
  	public function category()
    {
		return $this->belongsTo(Category::class,'category_id');
    }
  
  	public function company()
    {
		return $this->belongsTo(Company::class,'company_id');
    }
  
  	public function subCompany()
    {
		return $this->belongsTo(SubCompany::class,'sub_company_id');
    }
  
  	public function supplierCompany()
    {
		return $this->belongsTo(SupplierCompany::class,'supplier_company_id');
    }
  
  	public function products()
    {
		return $this->hasMany(RecordProduct::class);
    }
  
  	public function notifications()
    {
		return $this->hasMany(Notification::class);
    }
  
  	public function records()
    {
		return $this->hasMany(RecordProduct::class);
    }
  
  	public function transactionHistory()
    {
    	return $this->hasMany(TransactionAllocation::class, 'record_id');
    }
}
