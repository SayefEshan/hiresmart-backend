<?php

namespace App\Console\Commands;

use App\Jobs\MatchCandidatesToJob;
use App\Models\JobListing;
use Illuminate\Console\Command;

class ProcessJobMatching extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:match-candidates {--job-id= : Process specific job listing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process job matching for active job listings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $jobId = $this->option('job-id');

        if ($jobId) {
            $job = JobListing::find($jobId);
            if (!$job) {
                $this->error("Job listing with ID {$jobId} not found.");
                return Command::FAILURE;
            }

            $this->info("Processing job matching for job ID: {$jobId}");
            MatchCandidatesToJob::dispatch($job);
        } else {
            $this->info("Processing job matching for all active jobs...");

            $activeJobs = JobListing::active()
                ->whereDoesntHave('jobMatches', function ($query) {
                    // Skip jobs that were already matched in the last 24 hours
                    $query->where('created_at', '>=', now()->subDay());
                })
                ->get();

            $count = 0;
            foreach ($activeJobs as $job) {
                MatchCandidatesToJob::dispatch($job);
                $count++;
            }

            $this->info("Dispatched job matching for {$count} job listings.");
        }

        return Command::SUCCESS;
    }
}
