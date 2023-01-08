<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class myobtokens extends Model
{
    protected $fillable = [
        'access_token',
        'token_type',
        'expires_in',
        'refresh_token',
        'scope',
        'uuid',
        'username',
  ];
}
