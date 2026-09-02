<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_verifications', function (Blueprint $table) {

            $table->id();

            // User account requesting verification
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Name submitted for verification
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('extension_name')->nullable();

            // Birthday
            $table->date('birth_date');

            // Address submitted for verification
            $table->string('block')->nullable();
            $table->string('lot')->nullable();
            $table->string('unit')->nullable();
            $table->string('street')->nullable();
            $table->string('subdivision')->nullable();

            // Verification status
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            // Reason if verification was rejected
            $table->text('rejection_reason')->nullable();

            // Admin/user who reviewed the verification
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_verifications');
    }
};