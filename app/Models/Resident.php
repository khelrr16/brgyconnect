<?php

namespace App\Models;

use App\Models\BlotterRecord;
use App\Models\Immunization;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'birth_date',
        'sex',
        'civil_status',
        'citizenship',
        'place_of_birth',
        'contact_number',
        'registered_voter',
        'block',
        'lot',
        'unit',
        'street',
        'subdivision',
        'house_ownership',
        'relationship_to_head',
        'residence_since',
        'educational_attainment',
        'employment_status',
        'religion',
        'occupation',
        'monthly_income',
        'emergency_contact_name',
        'emergency_contact_number',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'monthly_income' => 'decimal:2',
    ];

    protected function address(): Attribute
    {
        return Attribute::make(
            get: function () {
                return collect([
                    $this->block !== null ? 'Block ' . $this->block : null,
                    $this->lot !== null ? 'Lot ' . $this->lot : null,
                    $this->street,
                    $this->subdivision,
                ])
                    ->filter(fn ($value) => filled($value))
                    ->implode(', ');
            }
        );
    }

    public function immunizations()
    {
        return $this->hasMany(Immunization::class);
    }

    public function blotterRecords()
    {
        return $this->hasMany(BlotterRecord::class);
    }
}
