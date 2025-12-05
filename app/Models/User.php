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
        'xp_this_week',
        'eula_accepted',
        'eula_accepted_at'
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

    public function robotSkins()
    {
        return $this->belongsToMany(RobotSkin::class, 'user_robot_skins', 'user_id', 'robot_skin_id')
            ->withPivot('is_equipped', 'purchased_at')
            ->withTimestamps();
    }

    public function equippedRobotSkin()
    {
        return $this->belongsToMany(RobotSkin::class, 'user_robot_skins', 'user_id', 'robot_skin_id')
            ->wherePivot('is_equipped', true)
            ->withPivot('is_equipped', 'purchased_at')
            ->withTimestamps()
            ->first();
    }

    // Calculate forum activity level based on posts, comments, and likes received
    public function getForumLevelAttribute(): int
    {
        $postsCount = $this->posts()->count();
        $commentsCount = $this->comments()->count();
        $likesReceivedCount = Like::whereHas('post', function($q) {
            $q->where('user_id', $this->user_id);
        })->count();

        $score = ($postsCount * 5) + ($commentsCount * 2) + ($likesReceivedCount * 1);

        // Level calculation: every 20 points = 1 level
        return (int) floor($score / 20) + 1;
    }

    // Get level badge information
    public function getLevelBadgeAttribute(): array
    {
        $level = $this->forum_level;

        if ($level <= 5) {
            return ['emoji' => '🌱', 'name' => 'Seedling', 'color' => '#81c784'];
        } elseif ($level <= 10) {
            return ['emoji' => '🌿', 'name' => 'Sprout', 'color' => '#66bb6a'];
        } elseif ($level <= 20) {
            return ['emoji' => '🌳', 'name' => 'Tree', 'color' => '#4caf50'];
        } else {
            return ['emoji' => '🌲', 'name' => 'Forest', 'color' => '#43a047'];
        }
    }

}

