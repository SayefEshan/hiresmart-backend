<?php

namespace App\Services;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JobListingService
{
    /**
     * Get paginated job listings for an employer
     */
    public function getEmployerJobListings(User $employer, array $filters = []): LengthAwarePaginator
    {
        $query = $employer->jobListings()
            ->withCount([
                'applications',
                'applications as pending_applications_count' => function ($query) {
                    $query->where('status', 'pending');
                },
                'applications as reviewed_applications_count' => function ($query) {
                    $query->where('status', 'reviewed');
                },
                'applications as shortlisted_applications_count' => function ($query) {
                    $query->where('status', 'shortlisted');
                }
            ]);

        // Apply filters
        if (isset($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->active();
            } elseif ($filters['status'] === 'archived') {
                $query->archived();
            }
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('location', 'like', '%' . $filters['search'] . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        $perPage = min(
            $filters['per_page'] ?? config('hiresmart.pagination.default_per_page'),
            config('hiresmart.pagination.max_per_page')
        );

        return $query->paginate($perPage);
    }

    /**
     * Create a new job listing
     */
    public function createJobListing(User $employer, array $data): JobListing
    {
        return DB::transaction(function () use ($employer, $data) {
            $jobListing = $employer->jobListings()->create($data);

            // Clear cache
            $this->clearJobListingsCache();

            return $jobListing->fresh();
        });
    }

    /**
     * Update a job listing
     */
    public function updateJobListing(JobListing $jobListing, array $data): JobListing
    {
        DB::transaction(function () use ($jobListing, $data) {
            $jobListing->update($data);

            // Clear cache
            $this->clearJobListingsCache();
        });

        return $jobListing->fresh();
    }

    /**
     * Delete a job listing
     */
    public function deleteJobListing(JobListing $jobListing): bool
    {
        $result = $jobListing->delete();

        // Clear cache
        $this->clearJobListingsCache();

        return $result;
    }

    /**
     * Get all active job listings with caching
     */
    public function getActiveJobListings(array $filters = []): LengthAwarePaginator
    {
        $cacheKey = 'job_listings:active:' . md5(serialize($filters));
        $cacheTTL = config('hiresmart.cache.ttl.job_listings');

        return Cache::remember($cacheKey, $cacheTTL, function () use ($filters) {
            $query = JobListing::active()
                ->with('employer:id,name');

            // Apply filters
            if (isset($filters['keyword'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['keyword'] . '%')
                        ->orWhere('description', 'like', '%' . $filters['keyword'] . '%');
                });
            }

            if (isset($filters['location'])) {
                $query->where('location', 'like', '%' . $filters['location'] . '%');
            }

            if (isset($filters['employment_type'])) {
                $query->where('employment_type', $filters['employment_type']);
            }

            if (isset($filters['min_salary'])) {
                $query->where('max_salary', '>=', $filters['min_salary']);
            }

            if (isset($filters['max_salary'])) {
                $query->where('min_salary', '<=', $filters['max_salary']);
            }

            $query->orderBy('created_at', 'desc');

            $perPage = min(
                $filters['per_page'] ?? config('hiresmart.pagination.default_per_page'),
                config('hiresmart.pagination.max_per_page')
            );

            return $query->paginate($perPage);
        });
    }

    /**
     * Clear job listings cache
     */
    private function clearJobListingsCache(): void
    {
        Cache::tags(['job_listings'])->flush();
    }
}
