<?php

namespace Database\Factories;

use App\Models\Household;
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
            'household_id' => Household::factory(),
            'resident_id' => $this->faker->unique()->numerify('RES-#####'),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional(0.7)->lastName(), 
            'last_name' => $this->faker->lastName(), 
            'extension_name' => $this->faker->optional(0.15)->randomElement(['Jr.', 'Sr.', 'II', 'III']), 
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'birth_date' => $this->faker->date(),
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widow/Widower', 'Divorced', 'Legally Separated']),
            'citizenship' => $this->faker->country(),
            'place_of_birth' => $this->faker->city(),
            'contact_number' => $this->faker->phoneNumber(),
            'registered_voter' => $this->faker->randomElement(['Yes - Within Barangay', 'Yes - Elsewhere', 'No']),

            'house_ownership' => $this->faker->randomElement(['Owned', 'Rented', 'Living with relatives', 'Other']),
            'relationship_to_head' => $this->faker->randomElement(['Head of Household', 'Spouse', 'Child', 'Parent', 'Sibling', 'Other']),
            'residence_since' => $this->faker->numberBetween(1990, date('Y')),

            'educational_attainment' => $this->faker->randomElement(['No Formal Education', 'Elementary Level', 'Elementary Graduate', 'High School Level', 'High School Graduate', 'Technical-Vocational', 'College Level', 'College Graduate', 'Postgraduate']),
            'employment_status' => $this->faker->randomElement(['Employed', 'Self-Employed', 'Unemployed', 'Student', 'Retired', 'Other']),
            'religion' => $this->faker->randomElement(['Roman Catholic', 'Christianity', 'Islam', 'Hinduism', 'Buddhism', 'Judaism', 'Other']),
            'occupation' => $this->faker->jobTitle(),
        ];
    }
}
