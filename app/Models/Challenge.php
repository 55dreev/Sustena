<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $table = 'challenges';
    protected $fillable = [
        'title', 'description', 'points', 'category', 'difficulty', 'streak_bonus'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'user_challenges');
    }
}
