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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('order_index');
            $table->timestamps();

            $table->index('course_id', 'idx_modules_course');
        });

        // Маҳдудияти CHECK барои тартиби рақамгузории модул
        try {
            DB::statement("ALTER TABLE modules ADD CONSTRAINT chk_modules_order CHECK (order_index >= 1)");
        } catch (\Throwable $e) {
            // Барои муҳитҳое, ки CHECK-ро дар сатҳи DDL дастгирӣ намекунанд
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
