<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootprintCategoryTotal extends Model
{
    protected $table = 'footprint_category_totals';
    protected $fillable = [
        'user_id', 'category', 'total_emission', 'created_at', 'updated_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
