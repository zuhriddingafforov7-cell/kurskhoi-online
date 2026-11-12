<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('course_id')->constrained('courses')->restrictOnDelete();
            $table->dateTime('enrolled_at')->useCurrent();
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id'], 'uq_enrollments_user_course');
            $table->index('user_id', 'idx_enrollments_user');
            $table->index('course_id', 'idx_enrollments_course');
        });

        try {
            DB::statement("ALTER TABLE enrollments ADD CONSTRAINT chk_enrollments_status CHECK (status IN ('active', 'completed', 'cancelled'))");
        } catch (\Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
