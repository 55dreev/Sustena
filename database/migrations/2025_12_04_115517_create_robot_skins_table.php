<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop tables if they exist (for re-running migration)
        Schema::dropIfExists('user_robot_skins');
        Schema::dropIfExists('robot_skins');

        Schema::create('robot_skins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('price')->default(0); // Cost in points/XP
            $table->integer('required_streak')->default(0); // 0 means no streak requirement
            $table->string('head_color')->default('#ffffff');
            $table->string('body_color')->default('#ffffff');
            $table->string('accent_color')->default('#2196f3');
            $table->string('special_effect')->nullable(); // For special animations or effects
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Pivot table for user's purchased skins
        Schema::create('user_robot_skins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('robot_skin_id')->constrained()->onDelete('cascade');
            $table->boolean('is_equipped')->default(false);
            $table->timestamp('purchased_at');
            $table->timestamps();

            // Foreign key constraint - users table uses user_id as primary key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_robot_skins');
        Schema::dropIfExists('robot_skins');
    }
};
