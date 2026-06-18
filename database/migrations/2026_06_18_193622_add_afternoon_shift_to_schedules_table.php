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
        Schema::table('schedules', function (Blueprint $table) {
            $table->time('afternoon_start_time')->nullable()->after('end_time');
            $table->time('afternoon_late_time')->nullable()->after('afternoon_start_time');
            $table->time('afternoon_checkout_start_time')->nullable()->after('afternoon_late_time');
            $table->time('afternoon_end_time')->nullable()->after('afternoon_checkout_start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn([
                'afternoon_start_time',
                'afternoon_late_time',
                'afternoon_checkout_start_time',
                'afternoon_end_time',
            ]);
        });
    }
};
