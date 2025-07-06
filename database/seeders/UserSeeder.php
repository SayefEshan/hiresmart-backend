<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating users with different roles...');

        // Create Admin Users
        $this->createAdminUsers();

        // Create Employer Users
        $this->createEmployerUsers();

        // Create Candidate Users
        $this->createCandidateUsers();

        $this->command->info('Users created successfully!');
    }

    private function createAdminUsers(): void
    {
        // Primary Admin
        $admin1 = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hiresmart.com',
            'password' => Hash::make('admin123'),
            'is_verified' => true,
        ]);
        $admin1->assignRole('admin');

        // Secondary Admin
        $admin2 = User::create([
            'name' => 'System Admin',
            'email' => 'system.admin@hiresmart.com',
            'password' => Hash::make('admin123'),
            'is_verified' => true,
        ]);
        $admin2->assignRole('admin');
    }

    private function createEmployerUsers(): void
    {
        // Employer 1 - Tech Company
        $employer1 = User::create([
            'name' => 'John Smith',
            'email' => 'john@techstartup.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $employer1->assignRole('employer');
        $employer1->employerProfile()->create([
            'company_name' => 'Tech Startup Inc',
            'company_description' => 'Innovative AI and ML solutions company',
            'industry' => 'Technology',
            'website' => 'https://techstartup.com',
            'location' => 'San Francisco, CA',
            'company_size' => '50-100',
        ]);

        // Employer 2 - Digital Agency
        $employer2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@digitalagency.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $employer2->assignRole('employer');
        $employer2->employerProfile()->create([
            'company_name' => 'Digital Agency Pro',
            'company_description' => 'Full-service digital marketing and web development',
            'industry' => 'Marketing',
            'website' => 'https://digitalagency.com',
            'location' => 'New York, NY',
            'company_size' => '100-500',
        ]);

        // Employer 3 - E-commerce
        $employer3 = User::create([
            'name' => 'Michael Chen',
            'email' => 'michael@ecommerce.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $employer3->assignRole('employer');
        $employer3->employerProfile()->create([
            'company_name' => 'E-Commerce Solutions Ltd',
            'company_description' => 'Leading e-commerce platform',
            'industry' => 'E-commerce',
            'website' => 'https://ecommerce.com',
            'location' => 'Remote',
            'company_size' => '500-1000',
        ]);

        // Employer 4 - Finance
        $employer4 = User::create([
            'name' => 'Robert Williams',
            'email' => 'robert@fintech.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $employer4->assignRole('employer');
        $employer4->employerProfile()->create([
            'company_name' => 'FinTech Innovations',
            'company_description' => 'Revolutionary financial technology solutions',
            'industry' => 'Finance',
            'website' => 'https://fintech.com',
            'location' => 'London, UK',
            'company_size' => '200-500',
        ]);

        // Unverified Employer
        $unverifiedEmployer = User::create([
            'name' => 'Unverified Employer',
            'email' => 'unverified.employer@test.com',
            'password' => Hash::make('password123'),
            'is_verified' => false,
            'created_at' => now()->subDays(10),
        ]);
        $unverifiedEmployer->assignRole('employer');
        $unverifiedEmployer->employerProfile()->create([
            'company_name' => 'Unverified Company',
        ]);
    }

    private function createCandidateUsers(): void
    {
        // Candidate 1 - Senior Developer
        $candidate1 = User::create([
            'name' => 'Alice Williams',
            'email' => 'alice@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $candidate1->assignRole('candidate');
        $candidate1->candidateProfile()->create([
            'phone' => '+1234567890',
            'bio' => 'Senior Full Stack Developer with 8+ years experience',
            'location' => 'Los Angeles, CA',
            'preferred_location' => 'Remote',
            'min_salary' => 100000,
            'max_salary' => 150000,
            'experience_years' => 8,
        ]);

        // Candidate 2 - Mid-level Developer
        $candidate2 = User::create([
            'name' => 'Bob Martinez',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $candidate2->assignRole('candidate');
        $candidate2->candidateProfile()->create([
            'phone' => '+1234567891',
            'bio' => 'Backend developer specializing in PHP and databases',
            'location' => 'Austin, TX',
            'preferred_location' => 'Austin, TX',
            'min_salary' => 70000,
            'max_salary' => 100000,
            'experience_years' => 4,
        ]);

        // Candidate 3 - Junior Developer
        $candidate3 = User::create([
            'name' => 'Emma Davis',
            'email' => 'emma@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $candidate3->assignRole('candidate');
        $candidate3->candidateProfile()->create([
            'phone' => '+1234567892',
            'bio' => 'Recent CS graduate passionate about web development',
            'location' => 'Chicago, IL',
            'preferred_location' => 'Chicago, IL',
            'min_salary' => 50000,
            'max_salary' => 70000,
            'experience_years' => 1,
        ]);

        // Candidate 4 - Frontend Specialist
        $candidate4 = User::create([
            'name' => 'David Wilson',
            'email' => 'david@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $candidate4->assignRole('candidate');
        $candidate4->candidateProfile()->create([
            'phone' => '+1234567893',
            'bio' => 'Frontend expert with React and Vue.js experience',
            'location' => 'Seattle, WA',
            'preferred_location' => 'Remote',
            'min_salary' => 80000,
            'max_salary' => 120000,
            'experience_years' => 5,
        ]);

        // Candidate 5 - DevOps Engineer
        $candidate5 = User::create([
            'name' => 'Frank Thompson',
            'email' => 'frank@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
        $candidate5->assignRole('candidate');
        $candidate5->candidateProfile()->create([
            'phone' => '+1234567894',
            'bio' => 'DevOps engineer with cloud architecture expertise',
            'location' => 'Denver, CO',
            'preferred_location' => 'Remote',
            'min_salary' => 90000,
            'max_salary' => 140000,
            'experience_years' => 6,
        ]);

        // Unverified Candidates
        $unverified1 = User::create([
            'name' => 'Unverified Candidate 1',
            'email' => 'unverified1@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => false,
            'created_at' => now()->subDays(8),
        ]);
        $unverified1->assignRole('candidate');
        $unverified1->candidateProfile()->create([]);

        $unverified2 = User::create([
            'name' => 'Unverified Candidate 2',
            'email' => 'unverified2@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => false,
            'created_at' => now()->subDays(15),
        ]);
        $unverified2->assignRole('candidate');
        $unverified2->candidateProfile()->create([]);
    }
}
