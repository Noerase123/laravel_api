<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $guard = 'admin';

    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}