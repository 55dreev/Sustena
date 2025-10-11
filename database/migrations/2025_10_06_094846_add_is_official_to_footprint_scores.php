// database/migrations/2025_10_06_000901_add_is_official_to_footprint_scores.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('footprint_scores', function (Blueprint $table) {
            $table->boolean('is_official')->default(false)->after('total_score');
            $table->index(['user_id','is_official','created_at']);
        });
    }
    public function down(): void {
        Schema::table('footprint_scores', function (Blueprint $table) {
            $table->dropIndex(['user_id','is_official','created_at']);
            $table->dropColumn('is_official');
        });
    }
};
