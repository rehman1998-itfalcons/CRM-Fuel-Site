<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    protected $fillable = [
    	'mailer',
    	'host',
    	'port',
    	'username',
    	'password',
    	'encryption',
    	'reply_to',
      	'primary_mail',
      	'primary_name',
      	'bcc',
      	'body'
    ];
}
