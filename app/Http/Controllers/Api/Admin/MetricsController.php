<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    /**
     * Get platform metrics for admin dashboard.
     */
    public function index(): JsonResponse
    {
        $metrics = [
            'users' => $this->getUserMetrics(),
            'jobs' => $this->getJobMetrics(),
            'applications' => $this->getApplicationMetrics(),
            'trends' => $this->getTrends(),
        ];

        return response()->json(['data' => $metrics]);
    }

    /**
     * Get user-related metrics.
     */
    private function getUserMetrics(): array
    {
        $totalUsers = User::count();
        $verifiedUsers = User::where('is_verified', true)->count();

        $usersByRole = User::select('roles.name as role', DB::raw('count(users.id) as count'))
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_type', User::class)
            ->groupBy('roles.name')
            ->pluck('count', 'role')
            ->toArray();

        return [
            'total' => $totalUsers,
            'verified' => $verifiedUsers,
            'unverified' => $totalUsers - $verifiedUsers,
            'by_role' => $usersByRole,
            'new_this_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'new_this_month' => User::where('created_at', '>=', now()->subMonth())->count(),
        ];
    }

    /**
     * Get job-related metrics.
     */
    private function getJobMetrics(): array
    {
        return [
            'total' => JobListing::count(),
            'active' => JobListing::active()->count(),
            'archived' => JobListing::archived()->count(),
            'by_type' => JobListing::active()
                ->select('employment_type', DB::raw('count(*) as count'))
                ->groupBy('employment_type')
                ->pluck('count', 'employment_type')
                ->toArray(),
            'posted_this_week' => JobListing::where('created_at', '>=', now()->subWeek())->count(),
            'posted_this_month' => JobListing::where('created_at', '>=', now()->subMonth())->count(),
            'average_salary' => [
                'min' => JobListing::active()->avg('min_salary'),
                'max' => JobListing::active()->avg('max_salary'),
            ],
        ];
    }

    /**
     * Get application-related metrics.
     */
    private function getApplicationMetrics(): array
    {
        return [
            'total' => Application::count(),
            'by_status' => Application::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'this_week' => Application::where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => Application::where('created_at', '>=', now()->subMonth())->count(),
            'average_per_job' => JobListing::has('applications')
                ->withCount('applications')
                ->get()
                ->avg('applications_count'),
        ];
    }

    /**
     * Get trend data for charts.
     */
    private function getTrends(): array
    {
        $thirtyDaysAgo = now()->subDays(30);

        // Daily applications for the last 30 days
        $applicationTrend = Application::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Daily job postings for the last 30 days
        $jobPostingTrend = JobListing::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        return [
            'applications' => $applicationTrend,
            'job_postings' => $jobPostingTrend,
        ];
    }
}
