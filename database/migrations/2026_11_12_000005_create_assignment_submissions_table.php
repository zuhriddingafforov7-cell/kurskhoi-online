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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->text('submission_text')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedSmallInteger('score')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status')->default('submitted'); // submitted, approved, rejected
            $table->timestamps();

            $table->unique(['assignment_id', 'user_id'], 'uq_submissions_assignment_user');
            $table->index('assignment_id', 'idx_submissions_assignment');
            $table->index('user_id', 'idx_submissions_user');
        });

        try {
            DB::statement("ALTER TABLE assignment_submissions ADD CONSTRAINT chk_submissions_score CHECK (score IS NULL OR (score >= 0 AND score <= 100))");
            DB::statement("ALTER TABLE assignment_submissions ADD CONSTRAINT chk_submissions_status CHECK (status IN ('submitted', 'approved', 'rejected'))");
        } catch (\Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
