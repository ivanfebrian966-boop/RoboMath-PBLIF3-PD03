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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('type', 50)->default('pilihan_ganda')->change();
            $table->json('options')->nullable()->change();
            $table->text('correct_answer')->change();
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->text('answer')->nullable()->change();
            $table->text('ai_feedback')->nullable()->after('is_correct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_attempts', 'ai_feedback')) {
                $table->dropColumn('ai_feedback');
            }
            $table->string('answer')->nullable()->change();
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('type')->default('pilihan_ganda')->change();
        });
    }
};
