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
            //
            $table->uuid('checkout_group_id')->nullable()->index();
            $table->string('stripe_session_id')->nullable()->index();
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->dropColumn([
                'checkout_group_id',
                'stripe_session_id',
                'stripe_payment_intent_id',
                'expires_at',
            ]);
        });
    }
};
