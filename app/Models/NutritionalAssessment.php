<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionalAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'immunization_id',
        'stage',
        'age_value',
        'age_unit',
        'length_cm',
        'weight_kg',
        'status',
        'assessment_date',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'length_cm'       => 'decimal:2',
        'weight_kg'       => 'decimal:2',
    ];

    // Status options differ per stage — enforce in FormRequest/Livewire rules, not here.
    public const STATUS_OPTIONS = [
        'newborn'      => ['low', 'normal', 'unknown'],
        'lbw_month_1'  => ['low', 'normal', 'unknown'],
        'lbw_month_2'  => ['low', 'normal', 'unknown'],
        'lbw_month_3'  => ['low', 'normal', 'unknown'],
        '1_3_months'   => ['stunted', 'wastedmam', 'wastedsam', 'obese', 'normal'],
        '6_11_months'  => ['low', 'normal', 'unknown'],
        '12_months'    => ['low', 'normal', 'unknown'],
    ];

    public function immunization()
    {
        return $this->belongsTo(Immunization::class);
    }
}