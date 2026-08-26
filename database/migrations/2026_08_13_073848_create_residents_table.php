<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            $table->string('resident_id')->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('extension_name')->nullable();

            $table->date('birth_date');
            $table->enum('sex', ['Male', 'Female', 'Other']);
            $table->string('civil_status');
            $table->string('citizenship');
            $table->string('place_of_birth');

            $table->string('contact_number')->nullable();
            $table->string('registered_voter');

            $table->string('block');
            $table->string('lot');
            $table->string('unit')->nullable();
            $table->string('street');
            $table->string('subdivision');
            $table->string('house_ownership');
            $table->string('relationship_to_head');
            $table->unsignedSmallInteger('residence_since');

            $table->string('educational_attainment');
            $table->string('employment_status');
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();
            $table->decimal('monthly_income', 12, 2)->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
