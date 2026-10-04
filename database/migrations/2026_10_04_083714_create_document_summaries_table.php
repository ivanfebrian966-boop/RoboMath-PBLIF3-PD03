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
        Schema::create('document_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('original_filename');
            $table->string('file_type', 20)->default('pdf');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_path')->nullable();
            $table->longText('extracted_text')->nullable();
            $table->longText('summary');
            $table->json('key_points')->nullable();
            $table->string('summary_style', 50)->default('ringkasan_eksekutif');
            $table->string('summary_length', 20)->default('sedang');
            $table->unsignedInteger('word_count_original')->default(0);
            $table->unsignedInteger('word_count_summary')->default(0);
            $table->string('ai_provider', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_summaries');
    }
};
