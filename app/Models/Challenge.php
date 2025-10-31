<?php

// app/Models/Challenge.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model {
  protected $fillable = ['title','subtitle','description','difficulty','points_xp','icon','is_active'];
}

