<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCompany extends Model
{
    protected $fillable = [
      'company_id',
      'category_id',
      'name',
      'phone_no',
      'address',
      'gst',
      'gst_status',
      'display_id',
      
      'account_id',

      'ac_uid',
      'status',
      'co_uid',
      'inv_due_days',
      'split_load'
    ];
  
  	public function emails()
    {
		return $this->hasMany(SubCompanyEmail::class);
    }
  
  	public function company()
    {
		return $this->belongsTo(Company::class);
    }
  
  	public function rates()
    {
		return $this->hasMany(SubCompanyRateArea::class);
    }
  
  	public function category()
    {
   		return $this->belongsTo(Category::class);   
    }
}
