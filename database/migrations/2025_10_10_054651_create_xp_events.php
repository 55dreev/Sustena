<?php

// database/migrations/2025_10_10_000002_create_xp_events.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('xp_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->uuid('attempt_id')->nullable()->index();
            $table->string('type', 50); // e.g., attempt_base, improvement_bonus, streak_bonus, practice_small
            $table->integer('xp'); // can be negative for adjustments
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['user_id','attempt_id','type'], 'uniq_user_attempt_type'); // idempotent per attempt/type
        });
    }
    public function down(): void {
        Schema::dropIfExists('xp_events');
    }
};

