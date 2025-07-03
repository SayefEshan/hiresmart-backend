<?php

namespace App\Services;

use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class ApplicationService
{
    /**
     * Apply to a job listing
     */
    public function applyToJob(User $candidate, JobListing $jobListing, array $data): Application
    {
        // Check rate limit
        $key = 'apply-job:' . $candidate->id;
        $maxAttempts = config('hiresmart.rate_limit.application_submission.attempts');

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            throw new \Exception("Too many applications. Please try again in {$seconds} seconds.", 429);
        }

        // Check if already applied
        if ($this->hasApplied($candidate, $jobListing)) {
            throw new \Exception('You have already applied to this job.', 400);
        }

        // Check if job is active
        if (!$jobListing->is_active || $jobListing->is_archived) {
            throw new \Exception('This job listing is no longer accepting applications.', 400);
        }

        return DB::transaction(function () use ($candidate, $jobListing, $data) {
            $application = $candidate->applications()->create([
                'job_listing_id' => $jobListing->id,
                'cover_letter' => $data['cover_letter'] ?? null,
                'status' => 'pending',
            ]);

            // Increment rate limiter
            RateLimiter::hit('apply-job:' . $candidate->id, config('hiresmart.rate_limit.application_submission.decay_minutes') * 60);

            return $application->fresh(['jobListing', 'candidate']);
        });
    }

    /**
     * Check if candidate has already applied to a job
     */
    public function hasApplied(User $candidate, JobListing $jobListing): bool
    {
        return Application::where('user_id', $candidate->id)
            ->where('job_listing_id', $jobListing->id)
            ->exists();
    }

    /**
     * Get candidate's applications
     */
    public function getCandidateApplications(User $candidate, array $filters = [])
    {
        $query = $candidate->applications()
            ->with(['jobListing.employer.employerProfile:id,user_id,company_name']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = min(
            $filters['per_page'] ?? config('hiresmart.pagination.default_per_page'),
            config('hiresmart.pagination.max_per_page')
        );

        return $query->paginate($perPage);
    }
}
