<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ArchiveOldJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:archive-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive job listings older than configured days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = config('hiresmart.job.archive_after_days', 30);
        $cutoffDate = now()->subDays($days);

        $this->info("Archiving jobs older than {$days} days...");

        $jobsToArchive = JobListing::where('is_active', true)
            ->where('is_archived', false)
            ->where('created_at', '<=', $cutoffDate)
            ->get();

        $count = 0;
        foreach ($jobsToArchive as $job) {
            $job->update([
                'is_archived' => true,
                'archived_at' => now(),
            ]);
            $count++;
        }

        $this->info("Archived {$count} job listings.");
        Log::info("Archived {$count} job listings older than {$days} days.");

        return Command::SUCCESS;
    }
}
