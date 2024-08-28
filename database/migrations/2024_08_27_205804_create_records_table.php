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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->integer('mileage')->unsigned()->default(0);
            $table->decimal('fuel_cost', 8, 2)->default(0);
            $table->integer('driving_hours')->unsigned()->default(0);
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->decimal('total_income', 8, 2)->default(0);
            $table->decimal('cash_income', 8, 2)->default(0);
            $table->decimal('nequi_income', 8, 2)->default(0);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
