<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MassMatch extends Model
{
    protected $fillable = [
    	'record_id',
      	'purchase_record_id'
    ];
  
  	public function record()
    {
		return $this->belongsTo(Record::class, 'record_id');
    }
  
  	public function purchaseRecord()
    {
		return $this->belongsTo(PurchaseRecord::class, 'purchase_record_id');
    }
}
