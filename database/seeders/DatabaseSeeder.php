<?php

namespace Database\Seeders;

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
        // Create test seeker user
        User::factory()->create([
            'name' => 'Job Seeker Demo',
            'email' => 'seeker@test.com',
            'role' => 'seeker',
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        // Create employer user
        User::factory()->create([
            'name' => 'Employer Demo',
            'email' => 'employer@test.com',
            'role' => 'employer',
        ]);
    }
}
