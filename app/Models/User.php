<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'username',
        'email',
        'password',
        'date_of_registration',
    ];

    public $timestamps = false;
}
