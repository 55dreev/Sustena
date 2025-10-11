<?php

// database/migrations/2025_10_11_000001_add_points_total_to_users.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'points_total')) {
                $table->unsignedBigInteger('points_total')->default(0)->after('xp_total');
            }
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'points_total')) {
                $table->dropColumn('points_total');
            }
        });
    }
};

