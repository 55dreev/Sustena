<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['post_id','user_id','content','status'];

    public function post(): BelongsTo
    {
        // FK comments.post_id -> posts.id
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function user(): BelongsTo
    {
        // FK comments.user_id -> users.user_id
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
