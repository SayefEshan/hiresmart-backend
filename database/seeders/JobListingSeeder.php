<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobListing;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating job listings...');

        $employers = User::role('employer')->get();

        if ($employers->isEmpty()) {
            $this->command->error('No employers found! Please run UserSeeder first.');
            return;
        }

        $skills = Skill::all()->keyBy('name');

        // Tech Startup Jobs
        $techEmployer = $employers->where('email', 'john@techstartup.com')->first();
        if ($techEmployer) {
            $this->createTechStartupJobs($techEmployer, $skills);
        }

        // Digital Agency Jobs
        $agencyEmployer = $employers->where('email', 'sarah@digitalagency.com')->first();
        if ($agencyEmployer) {
            $this->createDigitalAgencyJobs($agencyEmployer, $skills);
        }

        // E-commerce Jobs
        $ecommerceEmployer = $employers->where('email', 'michael@ecommerce.com')->first();
        if ($ecommerceEmployer) {
            $this->createEcommerceJobs($ecommerceEmployer, $skills);
        }

        // Fintech Jobs
        $fintechEmployer = $employers->where('email', 'robert@fintech.com')->first();
        if ($fintechEmployer) {
            $this->createFintechJobs($fintechEmployer, $skills);
        }

        $this->command->info('Job listings created successfully!');
    }

    private function createTechStartupJobs($employer, $skills): void
    {
        // Job 1: Senior Laravel Developer
        $job1 = $employer->jobListings()->create([
            'title' => 'Senior Laravel Developer',
            'description' => 'We are looking for an experienced Laravel developer to lead our backend development. You will architect scalable solutions, mentor junior developers, and work closely with our product team.',
            'location' => 'San Francisco, CA',
            'employment_type' => 'full-time',
            'min_salary' => 120000,
            'max_salary' => 160000,
            'experience_required' => 5,
            'is_active' => true,
        ]);

        $job1->skills()->attach([
            $skills['Laravel']->id => ['is_required' => true],
            $skills['PHP']->id => ['is_required' => true],
            $skills['MySQL']->id => ['is_required' => true],
            $skills['Redis']->id => ['is_required' => true],
            $skills['Docker']->id => ['is_required' => false],
            $skills['Vue.js']->id => ['is_required' => false],
        ]);

        // Job 2: DevOps Engineer
        $job2 = $employer->jobListings()->create([
            'title' => 'DevOps Engineer',
            'description' => 'Help us scale our infrastructure and improve deployment processes. Experience with cloud platforms and container orchestration required.',
            'location' => 'Remote',
            'employment_type' => 'full-time',
            'min_salary' => 110000,
            'max_salary' => 150000,
            'experience_required' => 4,
            'is_active' => true,
        ]);

        $job2->skills()->attach([
            $skills['Docker']->id => ['is_required' => true],
            $skills['Kubernetes']->id => ['is_required' => true],
            $skills['AWS']->id => ['is_required' => true],
            $skills['CI/CD']->id => ['is_required' => true],
            $skills['Git']->id => ['is_required' => true],
        ]);

        // Old job for archive testing
        $oldJob = $employer->jobListings()->create([
            'title' => 'Python Developer (Old Posting)',
            'description' => 'This is an old job posting that should be archived.',
            'location' => 'San Francisco, CA',
            'employment_type' => 'full-time',
            'min_salary' => 100000,
            'max_salary' => 130000,
            'experience_required' => 3,
            'is_active' => true,
            'created_at' => now()->subDays(35),
            'updated_at' => now()->subDays(35),
        ]);
    }

    private function createDigitalAgencyJobs($employer, $skills): void
    {
        // Job 3: Full Stack Developer
        $job3 = $employer->jobListings()->create([
            'title' => 'Full Stack Developer',
            'description' => 'Join our creative team to build web applications for our diverse client base. You will work on both frontend and backend development.',
            'location' => 'New York, NY',
            'employment_type' => 'full-time',
            'min_salary' => 90000,
            'max_salary' => 130000,
            'experience_required' => 3,
            'is_active' => true,
        ]);

        $job3->skills()->attach([
            $skills['React']->id => ['is_required' => true],
            $skills['Node.js']->id => ['is_required' => true],
            $skills['PostgreSQL']->id => ['is_required' => true],
            $skills['JavaScript']->id => ['is_required' => true],
        ]);

        // Job 4: Junior Frontend Developer
        $job4 = $employer->jobListings()->create([
            'title' => 'Junior Frontend Developer',
            'description' => 'Great opportunity for a junior developer to grow. Work with modern JavaScript frameworks and learn from experienced developers.',
            'location' => 'New York, NY',
            'employment_type' => 'full-time',
            'min_salary' => 60000,
            'max_salary' => 80000,
            'experience_required' => 1,
            'is_active' => true,
        ]);

        $job4->skills()->attach([
            $skills['JavaScript']->id => ['is_required' => true],
            $skills['React']->id => ['is_required' => true],
            $skills['Git']->id => ['is_required' => true],
        ]);

        // Job 5: UI/UX Designer (Inactive)
        $job5 = $employer->jobListings()->create([
            'title' => 'UI/UX Designer',
            'description' => 'Design beautiful and functional user interfaces.',
            'location' => 'New York, NY',
            'employment_type' => 'full-time',
            'min_salary' => 70000,
            'max_salary' => 100000,
            'experience_required' => 2,
            'is_active' => false,
        ]);
    }

    private function createEcommerceJobs($employer, $skills): void
    {
        // Job 6: Backend Engineer
        $job6 = $employer->jobListings()->create([
            'title' => 'Backend Engineer - PHP',
            'description' => 'Build and scale our e-commerce platform serving millions of users. Strong experience with high-traffic applications required.',
            'location' => 'Remote',
            'employment_type' => 'full-time',
            'min_salary' => 100000,
            'max_salary' => 140000,
            'experience_required' => 4,
            'is_active' => true,
        ]);

        $job6->skills()->attach([
            $skills['PHP']->id => ['is_required' => true],
            $skills['Laravel']->id => ['is_required' => true],
            $skills['MySQL']->id => ['is_required' => true],
            $skills['Redis']->id => ['is_required' => true],
            $skills['Elasticsearch']->id => ['is_required' => false],
        ]);

        // Job 7: React Native Developer
        $job7 = $employer->jobListings()->create([
            'title' => 'React Native Developer',
            'description' => 'Develop and maintain our mobile applications for iOS and Android platforms.',
            'location' => 'Remote',
            'employment_type' => 'contract',
            'min_salary' => 80000,
            'max_salary' => 120000,
            'experience_required' => 3,
            'is_active' => true,
        ]);

        $job7->skills()->attach([
            $skills['React']->id => ['is_required' => true],
            $skills['JavaScript']->id => ['is_required' => true],
            $skills['TypeScript']->id => ['is_required' => false],
        ]);

        // Job 8: Data Engineer
        $job8 = $employer->jobListings()->create([
            'title' => 'Data Engineer',
            'description' => 'Build data pipelines and analytics infrastructure for our e-commerce platform.',
            'location' => 'Remote',
            'employment_type' => 'full-time',
            'min_salary' => 110000,
            'max_salary' => 150000,
            'experience_required' => 5,
            'is_active' => true,
        ]);

        $job8->skills()->attach([
            $skills['Python']->id => ['is_required' => true],
            $skills['PostgreSQL']->id => ['is_required' => true],
            $skills['MongoDB']->id => ['is_required' => false],
        ]);
    }

    private function createFintechJobs($employer, $skills): void
    {
        // Job 9: Blockchain Developer
        $job9 = $employer->jobListings()->create([
            'title' => 'Blockchain Developer',
            'description' => 'Develop smart contracts and blockchain-based financial solutions.',
            'location' => 'London, UK',
            'employment_type' => 'full-time',
            'min_salary' => 130000,
            'max_salary' => 180000,
            'experience_required' => 4,
            'is_active' => true,
        ]);

        // Job 10: Security Engineer
        $job10 = $employer->jobListings()->create([
            'title' => 'Security Engineer',
            'description' => 'Ensure the security of our financial platform and protect customer data.',
            'location' => 'London, UK',
            'employment_type' => 'full-time',
            'min_salary' => 100000,
            'max_salary' => 140000,
            'experience_required' => 5,
            'is_active' => true,
        ]);

        // Archived job
        $archivedJob = $employer->jobListings()->create([
            'title' => 'Junior Developer (Archived)',
            'description' => 'This position has been archived.',
            'location' => 'London, UK',
            'employment_type' => 'full-time',
            'min_salary' => 50000,
            'max_salary' => 70000,
            'experience_required' => 1,
            'is_active' => false,
            'is_archived' => true,
            'archived_at' => now()->subDays(5),
        ]);
    }
}
