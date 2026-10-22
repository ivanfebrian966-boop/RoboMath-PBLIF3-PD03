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
        Schema::table('document_summaries', function (Blueprint $table) {
            $table->string('status', 20)->default('selesai')->after('ai_provider');
            $table->text('error_message')->nullable()->after('status');
            $table->index('status');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->string('review_status', 20)->default('approved')->after('is_active');
            $table->foreignId('reviewed_by')->nullable()->after('review_status')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->index(['review_status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropIndex(['review_status', 'is_active']);
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['review_status', 'reviewed_at']);
        });

        Schema::table('document_summaries', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'error_message']);
        });
    }
};
