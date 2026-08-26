<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotter_records', function (Blueprint $table) {
            $table->id();

            $table->string('blotter_number')->unique();

            // Optional link to a registered resident
            $table->foreignId('resident_id')
                ->nullable()
                ->constrained('residents')
                ->nullOnDelete();

            // User who recorded the blotter
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('incident_date');
            $table->time('incident_time')->nullable();

            $table->string('incident_type');
            $table->string('incident_location');

            $table->text('incident_description');

            $table->text('action_taken')->nullable();

            $table->enum('status', [
                'Pending',
                'Under Investigation',
                'Settled',
                'Referred',
                'Closed',
            ])->default('Pending');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotter_records');
    }
};