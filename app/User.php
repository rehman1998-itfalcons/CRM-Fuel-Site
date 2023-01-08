<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      	'role_id',
        'name',
      	'username',
      	'email',
      	'password',
      	'hint',
      	'photo',
      	'account_status',
      	'deleted_status',
      	'agreement',
      	'attachments',
      	'agreement_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

  	public function role()
    {
      return $this->belongsTo(Role::class);
    }

    public function loginSecurity()
{
    return $this->hasOne(LoginSecurity::class);
}
}
