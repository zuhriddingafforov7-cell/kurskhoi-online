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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->string('title');
            $table->string('content_type')->default('text'); // text, video, file
            $table->longText('body_text')->nullable();
            $table->string('video_url')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedInteger('order_index');
            $table->timestamps();

            $table->index('module_id', 'idx_lessons_module');
        });

        // Маҳдудиятҳои CHECK
        try {
            DB::statement("ALTER TABLE lessons ADD CONSTRAINT chk_lessons_order CHECK (order_index >= 1)");
            DB::statement("ALTER TABLE lessons ADD CONSTRAINT chk_lessons_type CHECK (content_type IN ('text', 'video', 'file'))");
        } catch (\Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
