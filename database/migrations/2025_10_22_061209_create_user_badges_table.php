<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();

            // If users.user_id is BIGINT PK, this matches it:
            $table->unsignedBigInteger('user_id')->index();

            $table->unsignedBigInteger('badge_id')->index();
            $table->timestamp('awarded_at');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id','badge_id']);

            // FK to users.user_id (NOT users.id)
            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');

            // FK to badges.id
            $table->foreign('badge_id')
                  ->references('id')->on('badges')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
