<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {

            $table->id();
            $table->string('household_id')->unique();

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            $table->string('block')->nullable();
            $table->string('lot')->nullable();
            $table->string('unit')->nullable();
            $table->string('street');
            $table->string('subdivision')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};