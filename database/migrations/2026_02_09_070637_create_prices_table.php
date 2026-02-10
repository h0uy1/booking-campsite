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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('tent_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('price_weekday', 10, 2);
            $table->decimal('price_weekend', 10, 2);
            $table->integer('capacity');
            $table->decimal('adult_price', 10, 2);
            $table->decimal('child_price', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
