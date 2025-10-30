<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'badges';
    protected $fillable = [
        'slug', 'name', 'icon', 'category', 'rule', 'points_reward'
    ];

    protected $casts = [
        'rule' => 'array',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}
