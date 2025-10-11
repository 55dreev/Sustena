<?php

// database/migrations/2025_10_11_000002_create_point_events_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('point_events', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');

    // FK must target users.user_id (not users.id)
    $table->foreign('user_id')
          ->references('user_id')->on('users')
          ->cascadeOnDelete();

    $table->string('attempt_id', 64)->nullable();
    $table->string('type', 64);
    $table->integer('points');
    $table->json('meta')->nullable();
    $table->timestamps();

    $table->unique(['user_id','attempt_id','type']);
    $table->index(['user_id','created_at']);
});

    }
    public function down(): void {
        Schema::dropIfExists('point_events');
    }
};

