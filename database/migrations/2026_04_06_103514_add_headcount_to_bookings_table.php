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
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'children_under_4')) {
                $table->dropColumn(['children_under_4', 'children_4_to_12', 'children_13_to_17']);
            }
            if (!Schema::hasColumn('bookings', 'adults')) {
                $table->integer('adults')->default(0)->after('status');
            }
            if (!Schema::hasColumn('bookings', 'children')) {
                $table->integer('children')->default(0)->after('adults');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'children')) {
                $table->dropColumn('children');
            }
        });
    }
};
