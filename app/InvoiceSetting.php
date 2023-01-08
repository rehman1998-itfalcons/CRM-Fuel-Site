<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    public $timestamps = false;
    protected $fillable=[
              'invoice_logo',
              'invoice_abn',
              'invoice_bank',
              'name',
              'invoice_bsb',
              'invoice_account_no',
              'invoice_web_url',
              'invoice_phone_no',
              'invoice_email',
              'invoice_address',
              'powerd_text',
              'pay_online_imges',
              'pay_online_text',
              'telephone_header_img1',
              'telephone_header_img2',
              'telephone_header',
              'telephone_text'
    ];
}
