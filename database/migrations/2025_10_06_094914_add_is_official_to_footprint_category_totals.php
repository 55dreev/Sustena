// database/migrations/2025_10_06_000902_add_is_official_to_footprint_category_totals.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('footprint_category_totals', function (Blueprint $table) {
            $table->boolean('is_official')->default(false)->after('answers_count');
            $table->index(['user_id','attempt_id','is_official']);
        });
    }
    public function down(): void {
        Schema::table('footprint_category_totals', function (Blueprint $table) {
            $table->dropIndex(['user_id','attempt_id','is_official']);
            $table->dropColumn('is_official');
        });
    }
};
