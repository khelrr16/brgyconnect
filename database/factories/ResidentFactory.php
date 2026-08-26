<?php

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resident_id' => $this->faker->unique()->numerify('RES-#####'),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional(0.7)->lastName(), 
            'last_name' => $this->faker->lastName(), 
            'extension_name' => $this->faker->optional(0.15)->randomElement(['Jr.', 'Sr.', 'II', 'III']), 
            'sex' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'birth_date' => $this->faker->date(),
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Divorced']),
            'citizenship' => $this->faker->country(),
            'place_of_birth' => $this->faker->city(),
            'contact_number' => $this->faker->phoneNumber(),
            'registered_voter' => $this->faker->randomElement(['Yes - Within Barangay', 'Yes - Elsewhere', 'No']),

            'block' => $this->faker->numberBetween(1, 20),
            'lot' => $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->optional(0.5)->buildingNumber(),
            'street' => $this->faker->streetName(),
            'subdivision' => $this->faker->randomElement(['Conpil I Village', 'Conpil III Executive', 'Console 1 Village', 'Greatland Village', 'Guevara Subdivision', 'Pacita 2A', 'Pacita 2B']),
            'house_ownership' => $this->faker->randomElement(['Owned', 'Rented', 'Living with relatives', 'Other']),
            'relationship_to_head' => $this->faker->randomElement(['Head of Household', 'Spouse', 'Child', 'Parent', 'Sibling', 'Other']),
            'residence_since' => $this->faker->numberBetween(1990, date('Y')),

            'educational_attainment' => $this->faker->randomElement(['No Formal Education', 'Elementary Level', 'Elementary Graduate', 'High School Level', 'High School Graduate', 'Technical-Vocational', 'College Level', 'College Graduate', 'Postgraduate']),
            'employment_status' => $this->faker->randomElement(['Employed', 'Self-Employed', 'Unemployed', 'Student', 'Retired', 'Other']),
            'religion' => $this->faker->randomElement(['Roman Catholic', 'Christianity', 'Islam', 'Hinduism', 'Buddhism', 'Judaism', 'Other']),
            'occupation' => $this->faker->jobTitle(),
            'monthly_income' => $this->faker->randomFloat(2, 0, 100000),

            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_number' => $this->faker->phoneNumber(),
        ];
    }
}
