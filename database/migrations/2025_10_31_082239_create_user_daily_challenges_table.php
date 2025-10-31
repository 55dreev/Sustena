<?php

// database/migrations/2025_10_31_000002_create_user_daily_challenges_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('user_daily_challenges', function (Blueprint $t) {
      $t->id();
      $t->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
      $t->foreignId('challenge_id')->constrained()->cascadeOnDelete();
      $t->date('date_for'); // e.g., today
      $t->enum('status', ['not-started','pending','completed'])->default('not-started');
      $t->string('proof_path')->nullable(); // uploaded image
      $t->timestamp('submitted_at')->nullable();
      $t->timestamp('completed_at')->nullable();
      $t->timestamps();

      $t->unique(['user_id','challenge_id','date_for']);
    });
  }
  public function down(): void {
    Schema::dropIfExists('user_daily_challenges');
  }
};

