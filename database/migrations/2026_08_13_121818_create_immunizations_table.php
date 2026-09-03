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
        Schema::create('immunizations', function (Blueprint $table) {
            $table->id();

            // Parent linkage — nullable FK to residents
            $table->foreignId('resident_id')->nullable()->constrained('residents')->nullOnDelete();
            $table->string('parent_first_name');
            $table->string('parent_last_name');
            $table->string('parent_middle_name')->nullable();
            // snapshot address fields (in case resident record changes later, or parent isn't a resident)
            $table->string('block')->nullable();
            $table->string('lot')->nullable();
            $table->string('unit')->nullable();
            $table->string('street')->nullable();
            $table->string('subdivision')->nullable();

            // Infant info
            $table->string('infant_first_name');
            $table->string('infant_last_name');
            $table->string('infant_middle_name')->nullable();
            $table->date('birthday');
            $table->enum('sex', ['male', 'female']);

            // Child Protected at Birth (CPAB)
            $table->boolean('cpab')->default(false);
            $table->enum('cpab_basis', ['tt1_td1_to_tt5_td5', 'tt3_td3_to_tt5_td5'])->nullable();

            $table->boolean('low_birth_weight')->default(false);

            // Breastfeeding milestones
            $table->boolean('breastfeeding_initiated')->nullable();
            $table->date('breastfeeding_initiated_date')->nullable();
            $table->boolean('exclusive_bf_5m29d')->nullable();
            $table->date('exclusive_bf_date')->nullable();
            $table->enum('complementary_feeding_status', ['continued_bf', 'no_longer_or_never_bf'])->nullable();

            // Vitamin A / MNP
            $table->date('vitamin_a_date')->nullable();
            $table->date('mnp_90_sachets_date')->nullable();
            $table->date('mnp_completed_date')->nullable();

            // Outcome
            $table->date('fic_date')->nullable();  // Fully Immunized Child
            $table->date('cic_date')->nullable();  // Completely Immunized Child

            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immunizations');
    }
};
