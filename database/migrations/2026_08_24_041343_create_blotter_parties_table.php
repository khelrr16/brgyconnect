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
        Schema::create('blotter_parties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blotter_record_id')
                ->constrained('blotter_records')
                ->cascadeOnDelete();

            // Filled when the person is a registered resident.
            // Null when the person is not registered.
            $table->foreignId('resident_id')
                ->nullable()
                ->constrained('residents')
                ->nullOnDelete();

            $table->enum('role', [
                'Complainant',
                'Respondent',
                'Witness',
            ]);

            // Used for registered or unregistered people.
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('extension_name')->nullable();

            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_parties');
    }
};
