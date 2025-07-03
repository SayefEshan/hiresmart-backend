<?php

namespace App\Http\Controllers\Api\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\ApplyJobRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\JobListing;
use App\Services\ApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationService $applicationService
    ) {}

    /**
     * Display a listing of the candidate's applications.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'status' => ['nullable', 'in:pending,reviewed,shortlisted,rejected'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $applications = $this->applicationService->getCandidateApplications(
            $request->user(),
            $filters
        );

        return ApplicationResource::collection($applications);
    }

    /**
     * Apply to a job listing.
     */
    public function apply(ApplyJobRequest $request, JobListing $jobListing): JsonResponse
    {
        try {
            $application = $this->applicationService->applyToJob(
                $request->user(),
                $jobListing,
                $request->validated()
            );

            return (new ApplicationResource($application))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_BAD_REQUEST;
            return response()->json([
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
}
