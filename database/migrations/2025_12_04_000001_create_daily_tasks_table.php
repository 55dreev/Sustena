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
        Schema::create('daily_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->text('task_description');
            $table->string('task_icon')->default('✓');
            $table->boolean('is_active')->default(true);
            $table->integer('xp_reward')->default(10);
            $table->timestamps();
        });

        Schema::create('user_task_completions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->date('completed_date');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('daily_tasks')->onDelete('cascade');
            $table->unique(['user_id', 'task_id', 'completed_date']);
        });

        // Insert default tasks
        DB::table('daily_tasks')->insert([
            [
                'task_name' => 'Use reusable water bottle',
                'task_description' => 'Avoid single-use plastic bottles today',
                'task_icon' => '💧',
                'xp_reward' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'task_name' => 'Walk instead of drive',
                'task_description' => 'Choose walking or cycling for short distances',
                'task_icon' => '🚶',
                'xp_reward' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'task_name' => 'Turn off unused lights',
                'task_description' => 'Save energy by switching off lights when not needed',
                'task_icon' => '💡',
                'xp_reward' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'task_name' => 'Recycle properly',
                'task_description' => 'Sort and recycle your waste correctly',
                'task_icon' => '♻️',
                'xp_reward' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'task_name' => 'Eat plant-based meal',
                'task_description' => 'Have at least one meat-free meal today',
                'task_icon' => '🥗',
                'xp_reward' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_task_completions');
        Schema::dropIfExists('daily_tasks');
    }
};
