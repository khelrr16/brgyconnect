<?php

namespace Database\Factories;

use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Household>
 */
class HouseholdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'household_id' => $this->faker->unique()->numerify('HH-######'),
            'block' => (string) $this->faker->numberBetween(1, 20),
            'lot' => (string) $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->optional(0.5)->buildingNumber(),
            'street' => $this->faker->streetName(),
            'subdivision' => $this->faker->randomElement([
                'Conpil I Village',
                'Conpil III Executive',
                'Console 1 Village',
                'Greatland Village',
                'Guevara Subdivision',
                'Pacita 2A',
                'Pacita 2B',
            ]),
        ];
    }
}