<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();           // e.g. carbon-under-100
            $table->string('name');                     // e.g. Carbon Under 100
            $table->string('icon')->nullable();         // emoji or class (e.g. ✅)
            $table->string('category')->nullable();     // energy, water, waste, carbon, meta
            $table->json('rule');                       // JSON rule (e.g. {"type":"threshold","fact":"weekly_kg","op":"<","value":100})
            $table->integer('points_reward')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
