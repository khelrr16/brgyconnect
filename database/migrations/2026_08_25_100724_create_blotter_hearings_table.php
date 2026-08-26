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
        Schema::create('blotter_hearings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blotter_record_id')
                ->constrained('blotter_records')
                ->cascadeOnDelete();

            $table->date('hearing_date');
            $table->time('hearing_time');

            $table->string('hearing_type')->nullable();

            $table->enum('status', [
                'Scheduled',
                'Completed',
                'Cancelled',
                'Postponed',
            ])->default('Scheduled');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_hearings');
    }
};
