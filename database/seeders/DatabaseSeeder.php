<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SkillsSeeder::class,
        ]);

        // Data seeders (run in order)
        $this->call([
            UserSeeder::class,           // Creates all users with different roles
            JobListingSeeder::class,     // Creates job listings for employers
            ApplicationSeeder::class,    // Creates applications from candidates
            CandidateSkillSeeder::class, // Assigns skills to candidates
        ]);
    }
}
