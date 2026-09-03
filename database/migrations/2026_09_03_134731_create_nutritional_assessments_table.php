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
        Schema::create('nutritional_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immunization_id')->constrained()->cascadeOnDelete();
            $table->string('stage'); // newborn, lbw_month_1, lbw_month_2, lbw_month_3, 1_3_months, 6_11_months, 12_months
            $table->unsignedSmallInteger('age_value');
            $table->enum('age_unit', ['weeks', 'months']);
            $table->decimal('length_cm', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->string('status'); // status options differ per stage — validate in code, not DB enum
            $table->date('assessment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutritional_assessments');
    }
};
