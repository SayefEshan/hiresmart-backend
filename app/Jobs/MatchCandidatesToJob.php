<?php

namespace App\Jobs;

use App\Models\CandidateProfile;
use App\Models\JobListing;
use App\Models\JobMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MatchCandidatesToJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public JobListing $jobListing
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Only process active jobs
        if (!$this->jobListing->is_active || $this->jobListing->is_archived) {
            return;
        }

        $candidates = CandidateProfile::with(['user', 'skills'])
            ->whereHas('user', function ($query) {
                $query->where('is_verified', true)
                    ->role('candidate');
            })
            ->get();

        foreach ($candidates as $candidateProfile) {
            $matchScore = $this->calculateMatchScore($candidateProfile);

            // Only create match if score is above threshold (e.g., 50%)
            if ($matchScore >= 50) {
                JobMatch::updateOrCreate(
                    [
                        'job_listing_id' => $this->jobListing->id,
                        'user_id' => $candidateProfile->user_id,
                    ],
                    [
                        'match_score' => $matchScore,
                        'match_criteria' => $this->getMatchCriteria($candidateProfile, $matchScore),
                    ]
                );

                // Queue notification job
                if ($matchScore >= 70) {
                    $this->dispatchNotification($candidateProfile->user_id, $matchScore);
                }
            }
        }

        Log::info("Job matching completed for job listing: {$this->jobListing->id}");
    }

    /**
     * Calculate match score based on various criteria.
     */
    private function calculateMatchScore(CandidateProfile $candidateProfile): float
    {
        $score = 0;
        $weights = [
            'skills' => 40,
            'location' => 20,
            'salary' => 20,
            'experience' => 20,
        ];

        // Skills matching
        $skillScore = $this->calculateSkillScore($candidateProfile);
        $score += $skillScore * $weights['skills'];

        // Location matching
        $locationScore = $this->calculateLocationScore($candidateProfile);
        $score += $locationScore * $weights['location'];

        // Salary range matching
        $salaryScore = $this->calculateSalaryScore($candidateProfile);
        $score += $salaryScore * $weights['salary'];

        // Experience matching
        $experienceScore = $this->calculateExperienceScore($candidateProfile);
        $score += $experienceScore * $weights['experience'];

        return round($score / 100, 2) * 100;
    }

    /**
     * Calculate skill matching score.
     */
    private function calculateSkillScore(CandidateProfile $candidateProfile): float
    {
        $jobSkills = $this->jobListing->skills;

        if ($jobSkills->isEmpty()) {
            return 100; // If no skills required, give full score
        }

        $candidateSkills = $candidateProfile->skills->pluck('id')->toArray();
        $requiredSkills = $jobSkills->where('pivot.is_required', true);
        $optionalSkills = $jobSkills->where('pivot.is_required', false);

        $matchedRequired = $requiredSkills->whereIn('id', $candidateSkills)->count();
        $matchedOptional = $optionalSkills->whereIn('id', $candidateSkills)->count();

        $requiredScore = $requiredSkills->count() > 0
            ? ($matchedRequired / $requiredSkills->count()) * 80
            : 80;

        $optionalScore = $optionalSkills->count() > 0
            ? ($matchedOptional / $optionalSkills->count()) * 20
            : 20;

        return $requiredScore + $optionalScore;
    }

    /**
     * Calculate location matching score.
     */
    private function calculateLocationScore(CandidateProfile $candidateProfile): float
    {
        // Simple location matching - can be enhanced with geolocation
        if (empty($candidateProfile->preferred_location)) {
            return 50; // Neutral score if no preference
        }

        if (
            stripos($this->jobListing->location, 'remote') !== false ||
            stripos($candidateProfile->preferred_location, 'remote') !== false
        ) {
            return 100;
        }

        if (
            stripos($this->jobListing->location, $candidateProfile->preferred_location) !== false ||
            stripos($candidateProfile->preferred_location, $this->jobListing->location) !== false
        ) {
            return 100;
        }

        return 0;
    }

    /**
     * Calculate salary matching score.
     */
    private function calculateSalaryScore(CandidateProfile $candidateProfile): float
    {
        if (
            !$this->jobListing->min_salary || !$this->jobListing->max_salary ||
            !$candidateProfile->min_salary || !$candidateProfile->max_salary
        ) {
            return 50; // Neutral score if salary not specified
        }

        $jobMid = ($this->jobListing->min_salary + $this->jobListing->max_salary) / 2;
        $candidateMid = ($candidateProfile->min_salary + $candidateProfile->max_salary) / 2;

        // Check if ranges overlap
        if (
            $this->jobListing->max_salary >= $candidateProfile->min_salary &&
            $this->jobListing->min_salary <= $candidateProfile->max_salary
        ) {

            // Calculate how close the midpoints are
            $difference = abs($jobMid - $candidateMid);
            $maxDifference = max($jobMid, $candidateMid);

            return max(0, 100 - ($difference / $maxDifference * 100));
        }

        return 0;
    }

    /**
     * Calculate experience matching score.
     */
    private function calculateExperienceScore(CandidateProfile $candidateProfile): float
    {
        $required = $this->jobListing->experience_required;
        $actual = $candidateProfile->experience_years;

        if ($actual >= $required) {
            return 100;
        }

        if ($required > 0) {
            return max(0, ($actual / $required) * 100);
        }

        return 100;
    }

    /**
     * Get detailed match criteria.
     */
    private function getMatchCriteria(CandidateProfile $candidateProfile, float $matchScore): array
    {
        return [
            'overall_score' => $matchScore,
            'skills_matched' => $this->calculateSkillScore($candidateProfile),
            'location_matched' => $this->calculateLocationScore($candidateProfile),
            'salary_matched' => $this->calculateSalaryScore($candidateProfile),
            'experience_matched' => $this->calculateExperienceScore($candidateProfile),
            'matched_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Dispatch notification for high-scoring matches.
     */
    private function dispatchNotification(int $userId, float $matchScore): void
    {
        // Log notification (in real app, would send email/push notification)
        Log::info("High match found! User: {$userId}, Job: {$this->jobListing->id}, Score: {$matchScore}%");

        // TODO: Dispatch actual notification job
        // NotifyMatchedCandidate::dispatch($userId, $this->jobListing->id, $matchScore);
    }
}
