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
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->after('student_id')->constrained()->nullOnDelete();
            $table->dateTime('check_in_at')->nullable()->after('scanned_at');
            $table->string('check_in_status')->nullable()->after('check_in_at'); // present, late
            $table->dateTime('check_out_at')->nullable()->after('check_in_status');
            $table->string('check_out_status')->nullable()->after('check_out_at'); // present, early_leave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['teacher_id', 'check_in_at', 'check_in_status', 'check_out_at', 'check_out_status']);
        });
    }
};
