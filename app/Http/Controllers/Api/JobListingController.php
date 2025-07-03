<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobListingResource;
use App\Models\JobListing;
use App\Services\JobListingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobListingController extends Controller
{
    public function __construct(
        private JobListingService $jobListingService
    ) {}

    /**
     * Display a listing of active jobs.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'keyword' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'in:full-time,part-time,contract,internship'],
            'min_salary' => ['nullable', 'numeric', 'min:0'],
            'max_salary' => ['nullable', 'numeric', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $jobListings = $this->jobListingService->getActiveJobListings($filters);

        return JobListingResource::collection($jobListings);
    }

    /**
     * Display the specified job listing.
     */
    public function show(JobListing $jobListing): JobListingResource
    {
        // Only show active job listings
        if (!$jobListing->is_active || $jobListing->is_archived) {
            abort(404, 'Job listing not found.');
        }

        $jobListing->load('employer.employerProfile:id,user_id,company_name,industry,location');

        return new JobListingResource($jobListing);
    }
}
