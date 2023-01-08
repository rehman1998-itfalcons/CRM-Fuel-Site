<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionAllocation extends Model
{
    protected $fillable = [
    	'sub_account_id',
    	'record_id',
    	'purchase_record_id',
        'expense_id',
    	'transaction_id',
        'amount',
        'payment_date'
    ];  
     public function records(){
        return $this->belongsTo(Record::class);
    }
}
