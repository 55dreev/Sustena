<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
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
