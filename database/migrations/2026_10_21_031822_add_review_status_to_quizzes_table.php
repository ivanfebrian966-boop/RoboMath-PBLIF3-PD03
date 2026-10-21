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
            $table->string('review_status', 20)->default('approved')->after('is_active');
            $table->text('admin_notes')->nullable()->after('review_status');
            $table->foreignId('reviewed_by')->nullable()->after('admin_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');

            $table->index(['review_status', 'topic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['review_status', 'topic_id']);
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['review_status', 'admin_notes', 'reviewed_at']);
        });
    }
};
