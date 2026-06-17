<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add performance indexes to attendances table.
     * 
     * These columns are frequently used in WHERE clauses across
     * DashboardController, ReportController, and AttendanceController.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->index('check_in_at');
            $table->index('check_out_at');
            $table->index('check_in_status');
            $table->index(['student_id', 'check_in_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['check_in_at']);
            $table->dropIndex(['check_out_at']);
            $table->dropIndex(['check_in_status']);
            $table->dropIndex(['student_id', 'check_in_at']);
        });
    }
};
