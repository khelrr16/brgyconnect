<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistance_requests', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Request ID
            |--------------------------------------------------------------------------
            */

            $table->string('request_id')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Applicant
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('resident_id')
                ->constrained('residents')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Source of Income
            |--------------------------------------------------------------------------
            */

            $table->enum('income_source', [
                'None',
                'Occupation',
                'Business',
            ]);

            $table->string('occupation')
                ->nullable();

            $table->decimal('monthly_income', 12, 2)
                ->nullable();

            $table->string('business_type')
                ->nullable();

            $table->unsignedInteger('business_duration')
                ->nullable()
                ->comment('Number of years in business');

            /*
            |--------------------------------------------------------------------------
            | Assistance
            |--------------------------------------------------------------------------
            */

            $table->enum('assistance_type', [
                'Educational Assistance',
                'Financial Assistance',
                'Burial Assistance',
                'Medical Assistance',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Addressed To
            |--------------------------------------------------------------------------
            |
            | Stores one or more agencies:
            | ["DSWD"]
            | ["DOH"]
            | ["DSWD", "DOH"]
            |
            */

            $table->json('addressed_to');

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Processing
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'processing',
                'approved',
                'rejected',
            ])->default('pending');

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('processed_at')
                ->nullable();

            $table->text('processing_notes')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('status');
            $table->index('assistance_type');
            $table->index('income_source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistance_requests');
    }
};