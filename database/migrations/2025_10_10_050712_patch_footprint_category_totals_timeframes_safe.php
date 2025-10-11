<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('footprint_category_totals', function (Blueprint $table) {
            if (!Schema::hasColumn('footprint_category_totals', 'basis')) {
                $table->string('basis', 10)->default('weekly')->after('answers_count');
            }
            if (!Schema::hasColumn('footprint_category_totals', 'timeframe')) {
                $table->string('timeframe', 10)->default('weekly')->after('basis');
            }
            if (!Schema::hasColumn('footprint_category_totals', 'period_start')) {
                $table->date('period_start')->nullable()->after('timeframe');
            }
            if (!Schema::hasColumn('footprint_category_totals', 'period_end')) {
                $table->date('period_end')->nullable()->after('period_start');
            }
            if (!Schema::hasColumn('footprint_category_totals', 'is_official')) {
                $table->boolean('is_official')->default(false)->after('period_end')->index();
            }
            if (!Schema::hasColumn('footprint_category_totals', 'kg_per_week')) {
                $table->decimal('kg_per_week', 10, 3)->default(0)->after('is_official');
            }

            // Indexes: only add if you’re sure they don’t exist.
            // Laravel has no hasIndex() helper; to be safe you can skip or give unique names
            // and add them manually later in SQL.
            // $table->index(['user_id','basis','category'], 'fct_user_basis_category_idx');
            // $table->index(['user_id','timeframe','category'], 'fct_user_timeframe_category_idx');
            // $table->index(['user_id','period_start','period_end'], 'fct_user_period_idx');
            // $table->index(['user_id','created_at'], 'fct_user_created_idx');
        });
    }

    public function down(): void
    {
        // No-op: we don’t drop here because columns may pre-exist from other migrations.
    }
};

