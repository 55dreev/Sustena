<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // app/Models/User.php
protected $primaryKey = 'user_id';
public $incrementing = true;
protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['username', 'email', 'password'];
}

