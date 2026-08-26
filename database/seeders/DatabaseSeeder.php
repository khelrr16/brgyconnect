<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Resident::factory(50)
        ->sequence(fn ($sequence) => [
            'resident_id' => 'RES-' . str_pad(
                $sequence->index + 1,
                6,
                '0',
                STR_PAD_LEFT
            ),
        ])
        ->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);
    }
}
