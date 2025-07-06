<?php

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreJobListingRequest;
use App\Http\Requests\Employer\UpdateJobListingRequest;
use App\Http\Resources\JobListingResource;
use App\Models\JobListing;
use App\Services\JobListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class JobListingController extends Controller
{
    public function __construct(
        private JobListingService $jobListingService
    ) {}

    /**
     * Display a listing of the employer's job listings.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['status', 'search', 'per_page']);

        $jobListings = $this->jobListingService->getEmployerJobListings(
            $request->user(),
            $filters
        );

        return JobListingResource::collection($jobListings);
    }

    /**
     * Store a newly created job listing.
     */
    public function store(StoreJobListingRequest $request): JsonResponse
    {

        $jobListing = $this->jobListingService->createJobListing(
            $request->user(),
            $request->validated()
        );

        return (new JobListingResource($jobListing))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified job listing.
     */
    public function show(JobListing $job): JobListingResource
    {
        // Check if user owns this job listing
        if (!$job->isOwnedBy(request()->user())) {
            abort(403, 'Unauthorized access to this job listing.');
        }

        $job->loadCount([
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

        return new JobListingResource($job);
    }

    /**
     * Update the specified job listing.
     */
    public function update(UpdateJobListingRequest $request, JobListing $job): JobListingResource
    {
        // Check if user owns this job listing
        if (!$job->isOwnedBy($request->user())) {
            abort(403, 'Unauthorized access to this job listing.');
        }

        $updatedJob = $this->jobListingService->updateJobListing(
            $job,
            $request->validated()
        );

        return new JobListingResource($updatedJob);
    }

    /**
     * Remove the specified job listing.
     */
    public function destroy(Request $request, JobListing $job): JsonResponse
    {
        // Check if user owns this job listing
        if (!$job->isOwnedBy($request->user())) {
            abort(403, 'Unauthorized access to this job listing.');
        }

        $this->jobListingService->deleteJobListing($job);

        return response()->json([
            'message' => 'Job listing deleted successfully.'
        ], Response::HTTP_OK);
    }
}
