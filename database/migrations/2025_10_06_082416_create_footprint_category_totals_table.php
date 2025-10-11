<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('footprint_category_totals', function (Blueprint $table) {
            $table->id();

            // FK to users.user_id (BIGINT)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->cascadeOnDelete();

            // One quiz run identifier (UUID)
            $table->uuid('attempt_id');

            $table->string('category', 100);
            $table->decimal('total_score', 8, 2)->default(0);
            $table->unsignedInteger('answers_count')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'attempt_id']);
            $table->index(['user_id', 'category']);

            // Prevent duplicates per user+attempt+category
            $table->unique(['user_id', 'attempt_id', 'category'], 'uniq_user_attempt_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footprint_category_totals');
    }
};
