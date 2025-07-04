<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remove-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unverified users older than configured days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = config('hiresmart.user.remove_unverified_after_days', 7);
        $cutoffDate = now()->subDays($days);

        $this->info("Removing unverified users older than {$days} days...");

        $usersToRemove = User::where('is_verified', false)
            ->where('created_at', '<=', $cutoffDate)
            ->whereDoesntHave('jobListings')
            ->whereDoesntHave('applications')
            ->get();

        $count = 0;
        foreach ($usersToRemove as $user) {
            // Delete related profiles first
            $user->employerProfile()->delete();
            $user->candidateProfile()->delete();

            // Delete the user
            $user->delete();
            $count++;
        }

        $this->info("Removed {$count} unverified users.");
        Log::info("Removed {$count} unverified users older than {$days} days.");

        return Command::SUCCESS;
    }
}
