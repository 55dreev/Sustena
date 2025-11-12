<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // app/Models/User.php
protected $primaryKey = 'user_id';
public $incrementing = true;
protected $keyType = 'int';

    public $timestamps = false;

    protected $casts = ['is_admin' => 'boolean'];
     protected $fillable = [
        'username',
        'email',
        'password',
        'google_id',
        'xp_total',
        'points_total',
        'level',
        'streak_weeks',
        'last_official_week',
        'last_xp_awarded_at',
        'xp_today',
        'xp_this_week'
    ];
    // Optional helper for a display label
    public function getLabelAttribute(): string
    {
        return $this->username ?? $this->email ?? ('User #'.$this->user_id);
    }
    public function getDisplayNameAttribute(): string
    {
        $full = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
        return $this->name
            ?? $this->username
            ?? $this->full_name
            ?? $this->email
            ?? 'User';
    }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id', 'user_id');
    }

}

