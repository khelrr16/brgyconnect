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
        Schema::create('vaccine_doses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immunization_id')->constrained()->cascadeOnDelete();
            $table->string('vaccine'); // BCG, HEPB_BD, DPT_HIB_HEPB, OPV, PCV, IPV, MMR
            $table->unsignedTinyInteger('dose_number');
            $table->date('date_given')->nullable();
            $table->timestamps();
            $table->unique(['immunization_id', 'vaccine', 'dose_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_doses');
    }
};
