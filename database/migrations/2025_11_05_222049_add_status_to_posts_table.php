<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_add_status_to_posts_table.php
public function up(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->string('status')->default('Pending')->index();
    });
}
public function down(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
