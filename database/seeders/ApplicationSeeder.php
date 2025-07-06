<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobListing;
use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating job applications...');

        $candidates = User::role('candidate')->where('is_verified', true)->get();
        $jobListings = JobListing::where('is_active', true)->where('is_archived', false)->get();

        if ($candidates->isEmpty() || $jobListings->isEmpty()) {
            $this->command->error('No candidates or active job listings found! Please run UserSeeder and JobListingSeeder first.');
            return;
        }

        // Alice (Senior Dev) applications
        $alice = $candidates->where('email', 'alice@example.com')->first();
        if ($alice) {
            $this->createAliceApplications($alice, $jobListings);
        }

        // Bob (Mid-level Dev) applications
        $bob = $candidates->where('email', 'bob@example.com')->first();
        if ($bob) {
            $this->createBobApplications($bob, $jobListings);
        }

        // Emma (Junior Dev) applications
        $emma = $candidates->where('email', 'emma@example.com')->first();
        if ($emma) {
            $this->createEmmaApplications($emma, $jobListings);
        }

        // David (Frontend Dev) applications
        $david = $candidates->where('email', 'david@example.com')->first();
        if ($david) {
            $this->createDavidApplications($david, $jobListings);
        }

        // Frank (DevOps) applications
        $frank = $candidates->where('email', 'frank@example.com')->first();
        if ($frank) {
            $this->createFrankApplications($frank, $jobListings);
        }

        $this->command->info('Applications created successfully!');
    }

    private function createAliceApplications($alice, $jobListings): void
    {
        // Apply to Senior Laravel Developer
        $seniorLaravelJob = $jobListings->where('title', 'Senior Laravel Developer')->first();
        if ($seniorLaravelJob) {
            Application::create([
                'job_listing_id' => $seniorLaravelJob->id,
                'user_id' => $alice->id,
                'cover_letter' => 'With 8 years of experience in Laravel development, I have successfully led multiple projects from conception to deployment. I am excited about the opportunity to bring my expertise in scalable architecture and team leadership to your organization.',
                'status' => 'shortlisted',
                'applied_at' => now()->subDays(3),
            ]);
        }

        // Apply to Backend Engineer
        $backendJob = $jobListings->where('title', 'Backend Engineer - PHP')->first();
        if ($backendJob) {
            Application::create([
                'job_listing_id' => $backendJob->id,
                'user_id' => $alice->id,
                'cover_letter' => 'I have extensive experience building high-performance e-commerce platforms. My expertise in PHP, Laravel, and database optimization would be valuable for scaling your platform.',
                'status' => 'reviewed',
                'applied_at' => now()->subDays(5),
            ]);
        }

        // Apply to Full Stack Developer
        $fullStackJob = $jobListings->where('title', 'Full Stack Developer')->first();
        if ($fullStackJob) {
            Application::create([
                'job_listing_id' => $fullStackJob->id,
                'user_id' => $alice->id,
                'cover_letter' => 'As a senior developer with both frontend and backend expertise, I can contribute to all aspects of your client projects.',
                'status' => 'pending',
                'applied_at' => now()->subDays(1),
            ]);
        }
    }

    private function createBobApplications($bob, $jobListings): void
    {
        // Apply to Backend Engineer
        $backendJob = $jobListings->where('title', 'Backend Engineer - PHP')->first();
        if ($backendJob) {
            Application::create([
                'job_listing_id' => $backendJob->id,
                'user_id' => $bob->id,
                'cover_letter' => 'I am a backend developer with 4 years of experience in PHP and Laravel. I have worked on several high-traffic applications and understand the importance of scalable architecture.',
                'status' => 'pending',
                'applied_at' => now()->subDays(2),
            ]);
        }

        // Apply to Full Stack Developer
        $fullStackJob = $jobListings->where('title', 'Full Stack Developer')->first();
        if ($fullStackJob) {
            Application::create([
                'job_listing_id' => $fullStackJob->id,
                'user_id' => $bob->id,
                'cover_letter' => 'While my primary expertise is in backend development, I have been expanding my frontend skills and would love to work in a full-stack role.',
                'status' => 'reviewed',
                'applied_at' => now()->subDays(4),
            ]);
        }
    }

    private function createEmmaApplications($emma, $jobListings): void
    {
        // Apply to Junior Frontend Developer
        $juniorJob = $jobListings->where('title', 'Junior Frontend Developer')->first();
        if ($juniorJob) {
            Application::create([
                'job_listing_id' => $juniorJob->id,
                'user_id' => $emma->id,
                'cover_letter' => 'As a recent CS graduate with a passion for frontend development, I am excited about this opportunity to start my career. I have completed several React projects during my studies and internship.',
                'status' => 'shortlisted',
                'applied_at' => now()->subDays(6),
            ]);
        }
    }

    private function createDavidApplications($david, $jobListings): void
    {
        // Apply to React Native Developer
        $reactNativeJob = $jobListings->where('title', 'React Native Developer')->first();
        if ($reactNativeJob) {
            Application::create([
                'job_listing_id' => $reactNativeJob->id,
                'user_id' => $david->id,
                'cover_letter' => 'With 5 years of experience in React and 2 years specifically in React Native, I have delivered multiple successful mobile applications. I am passionate about creating smooth, performant mobile experiences.',
                'status' => 'pending',
                'applied_at' => now()->subHours(12),
            ]);
        }

        // Apply to Full Stack Developer
        $fullStackJob = $jobListings->where('title', 'Full Stack Developer')->first();
        if ($fullStackJob) {
            Application::create([
                'job_listing_id' => $fullStackJob->id,
                'user_id' => $david->id,
                'cover_letter' => 'I am a frontend specialist looking to expand into full-stack development. My strong JavaScript skills and experience with React would be valuable for your projects.',
                'status' => 'rejected',
                'applied_at' => now()->subDays(7),
            ]);
        }

        // Apply to Junior Frontend Developer (overqualified)
        $juniorJob = $jobListings->where('title', 'Junior Frontend Developer')->first();
        if ($juniorJob) {
            Application::create([
                'job_listing_id' => $juniorJob->id,
                'user_id' => $david->id,
                'status' => 'pending',
                'applied_at' => now()->subDays(1),
            ]);
        }
    }

    private function createFrankApplications($frank, $jobListings): void
    {
        // Apply to DevOps Engineer
        $devOpsJob = $jobListings->where('title', 'DevOps Engineer')->first();
        if ($devOpsJob) {
            Application::create([
                'job_listing_id' => $devOpsJob->id,
                'user_id' => $frank->id,
                'cover_letter' => 'I am a DevOps engineer with 6 years of experience in cloud architecture and container orchestration. I have successfully implemented CI/CD pipelines and managed infrastructure for multiple high-traffic applications.',
                'status' => 'shortlisted',
                'applied_at' => now()->subDays(2),
            ]);
        }

        // Apply to Security Engineer
        $securityJob = $jobListings->where('title', 'Security Engineer')->first();
        if ($securityJob) {
            Application::create([
                'job_listing_id' => $securityJob->id,
                'user_id' => $frank->id,
                'cover_letter' => 'My DevOps background includes extensive security practices. I have implemented security scanning in CI/CD pipelines and have experience with infrastructure security.',
                'status' => 'reviewed',
                'applied_at' => now()->subDays(3),
            ]);
        }
    }
}
