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
    Schema::table('footprint_category_totals', function (Blueprint $table) {
        $table->string('basis', 10)->default('weekly')->after('answers_count');
        $table->string('timeframe', 10)->default('weekly')->after('basis');
        $table->date('period_start')->nullable()->after('timeframe');
        $table->date('period_end')->nullable()->after('period_start');
        $table->boolean('is_official')->default(false)->after('period_end')->index();
        $table->decimal('kg_per_week', 10, 3)->default(0)->after('is_official');

        $table->index(['user_id', 'basis', 'category']);
        $table->index(['user_id', 'timeframe', 'category']);
        $table->index(['user_id', 'period_start', 'period_end']);
        $table->index(['user_id', 'created_at']);
    });
}

public function down(): void
{
    Schema::table('footprint_category_totals', function (Blueprint $table) {
        $table->dropColumn(['basis','timeframe','period_start','period_end','is_official','kg_per_week']);
    });
}

};
