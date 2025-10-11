<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('footprint_scores', function (Blueprint $table) {
            $table->id();

            // FK to users.user_id (BIGINT)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->uuid('attempt_id');
            $table->decimal('total_score', 8, 2)->default(0);

            $table->timestamps();

            // One overall row per attempt per user
            $table->unique(['user_id', 'attempt_id'], 'uniq_user_attempt');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footprint_scores');
    }
};
