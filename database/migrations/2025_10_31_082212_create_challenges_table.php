<?php

// database/migrations/2025_10_31_000001_create_challenges_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('challenges', function (Blueprint $t) {
      $t->id();
      $t->string('title')->unique();
      $t->string('subtitle')->nullable();
      $t->text('description')->nullable();
      $t->unsignedTinyInteger('difficulty')->default(1); // 1 easy, 2 med, 3 hard
      $t->unsignedSmallInteger('points_xp')->default(0); // numeric XP
      $t->string('icon')->default('🌱'); // emoji or icon key
      $t->boolean('is_active')->default(true);
      $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('challenges');
  }
};
