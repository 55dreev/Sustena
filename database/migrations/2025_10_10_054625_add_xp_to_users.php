<?php

// database/migrations/2025_10_10_000001_add_xp_to_users.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('xp_total')->default(0)->after('remember_token');
            $table->unsignedInteger('level')->default(1)->after('xp_total');
            $table->unsignedInteger('streak_weeks')->default(0)->after('level');
            $table->date('last_official_week')->nullable()->after('streak_weeks'); // ISO week marker (e.g., 2025-10-06)
            $table->timestamp('last_xp_awarded_at')->nullable()->after('last_official_week');
            $table->unsignedInteger('xp_today')->default(0)->after('last_xp_awarded_at');
            $table->unsignedInteger('xp_this_week')->default(0)->after('xp_today');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['xp_total','level','streak_weeks','last_official_week','last_xp_awarded_at','xp_today','xp_this_week']);
        });
    }
};

