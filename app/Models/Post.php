<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Post extends Model
{
    protected $fillable = ['title', 'content', 'user_id', 'status'];

    public function user(): BelongsTo
    {
        // FK posts.user_id -> users.user_id
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function comments(): HasMany
    {
        // FK comments.post_id -> posts.id (only approved)
        return $this->hasMany(Comment::class, 'post_id', 'id')
            ->where('status', 'Approved')
            ->latest();
    }

    public function allComments(): HasMany
    {
        // FK comments.post_id -> posts.id (all statuses for moderation)
        return $this->hasMany(Comment::class, 'post_id', 'id')->latest();
    }

    public function likes(): HasMany
    {
        // FK likes.post_id -> posts.id
        return $this->hasMany(Like::class, 'post_id', 'id');
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->user_id)->exists();
    }
}
