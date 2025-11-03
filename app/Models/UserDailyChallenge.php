<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDailyChallenge extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'date_for',
        'status',
        'proof_path',
        'submitted_at',
        'completed_at',
    ];

    protected $casts = [
        'date_for' => 'date',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function user(): BelongsTo
    {
         return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
