<?php

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ApplicationController extends Controller
{
    /**
     * Display applications for a specific job listing.
     */
    public function index(Request $request, JobListing $jobListing): AnonymousResourceCollection
    {
        // Check if employer owns this job listing
        if (!$jobListing->isOwnedBy($request->user())) {
            abort(403, 'Unauthorized access to this job listing.');
        }

        $filters = $request->validate([
            'status' => ['nullable', 'in:pending,reviewed,shortlisted,rejected'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = $jobListing->applications()
            ->with('candidate.candidateProfile');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = min(
            $filters['per_page'] ?? config('hiresmart.pagination.default_per_page'),
            config('hiresmart.pagination.max_per_page')
        );

        $applications = $query->paginate($perPage);

        // Cache application stats for employer dashboard
        $this->cacheApplicationStats($request->user()->id, $jobListing->id);

        return ApplicationResource::collection($applications);
    }

    /**
     * Update application status.
     */
    public function updateStatus(Request $request, JobListing $jobListing, Application $application): JsonResponse
    {
        // Check if employer owns this job listing
        if (!$jobListing->isOwnedBy($request->user())) {
            abort(403, 'Unauthorized access to this job listing.');
        }

        // Check if application belongs to this job listing
        if ($application->job_listing_id !== $jobListing->id) {
            abort(404, 'Application not found for this job listing.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:reviewed,shortlisted,rejected'],
        ]);

        $application->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Application status updated successfully.',
            'application' => new ApplicationResource($application->fresh('candidate')),
        ]);
    }

    /**
     * Cache application statistics for employer.
     */
    private function cacheApplicationStats(int $employerId, int $jobListingId): void
    {
        $cacheKey = "employer:{$employerId}:job:{$jobListingId}:stats";
        $cacheTTL = config('hiresmart.cache.ttl.application_stats');

        Cache::remember($cacheKey, $cacheTTL, function () use ($jobListingId) {
            return Application::where('job_listing_id', $jobListingId)
                ->selectRaw('
                    count(*) as total,
                    sum(case when status = \'pending\' then 1 else 0 end) as pending,
                    sum(case when status = \'reviewed\' then 1 else 0 end) as reviewed,
                    sum(case when status = \'shortlisted\' then 1 else 0 end) as shortlisted,
                    sum(case when status = \'rejected\' then 1 else 0 end) as rejected
                ')
                ->first();
        });
    }
}
