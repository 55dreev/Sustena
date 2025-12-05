<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RobotSkin extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'required_streak',
        'head_color',
        'body_color',
        'accent_color',
        'special_effect',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Users who have purchased this skin
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_robot_skins', 'robot_skin_id', 'user_id')
            ->withPivot('is_equipped', 'purchased_at')
            ->withTimestamps();
    }

    /**
     * Check if skin is unlocked for a given streak count
     */
    public function isUnlockedForStreak($streakDays)
    {
        return $streakDays >= $this->required_streak;
    }

    /**
     * Check if skin is available for purchase by user
     */
    public function isAvailableFor($user)
    {
        $streakDays = $user->streak_days ?? 0;
        return $this->isUnlockedForStreak($streakDays);
    }
}
