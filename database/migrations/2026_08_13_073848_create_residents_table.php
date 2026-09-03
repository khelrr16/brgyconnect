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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id') 
                ->nullable()
                ->constrained('households') 
                ->nullOnDelete();

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
            $table->string('house_ownership');
            $table->string('relationship_to_head');
            $table->unsignedSmallInteger('residence_since');

            $table->string('educational_attainment');
            $table->string('out_of_school')->nullable();
            $table->string('employment_status');
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();
            $table->string('is_ofw')->default('No');
            $table->string('ofw_country')->nullable();
            $table->string('is_pwd')->nullable();
            $table->string('is_indigenous')->default('No');
            $table->string('indigenous_group')->nullable();
            $table->string('is_solo_parent')->nullable();

            $table->index('household_id');
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
