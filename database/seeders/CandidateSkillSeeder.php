<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Assigning skills to candidates...');

        $skills = Skill::all()->keyBy('name');
        $candidates = User::role('candidate')->where('is_verified', true)->get();

        if ($skills->isEmpty() || $candidates->isEmpty()) {
            $this->command->error('No skills or candidates found! Please run SkillsSeeder and UserSeeder first.');
            return;
        }

        // Alice - Senior Full Stack Developer
        $alice = $candidates->where('email', 'alice@example.com')->first();
        if ($alice && $alice->candidateProfile) {
            $alice->candidateProfile->skills()->attach([
                $skills['PHP']->id => ['proficiency' => 'expert'],
                $skills['Laravel']->id => ['proficiency' => 'expert'],
                $skills['JavaScript']->id => ['proficiency' => 'advanced'],
                $skills['React']->id => ['proficiency' => 'advanced'],
                $skills['Vue.js']->id => ['proficiency' => 'intermediate'],
                $skills['MySQL']->id => ['proficiency' => 'expert'],
                $skills['PostgreSQL']->id => ['proficiency' => 'advanced'],
                $skills['Redis']->id => ['proficiency' => 'advanced'],
                $skills['Docker']->id => ['proficiency' => 'intermediate'],
                $skills['Git']->id => ['proficiency' => 'expert'],
                $skills['REST API']->id => ['proficiency' => 'expert'],
                $skills['TDD']->id => ['proficiency' => 'advanced'],
            ]);
        }

        // Bob - Backend Developer
        $bob = $candidates->where('email', 'bob@example.com')->first();
        if ($bob && $bob->candidateProfile) {
            $bob->candidateProfile->skills()->attach([
                $skills['PHP']->id => ['proficiency' => 'advanced'],
                $skills['Laravel']->id => ['proficiency' => 'intermediate'],
                $skills['MySQL']->id => ['proficiency' => 'advanced'],
                $skills['PostgreSQL']->id => ['proficiency' => 'intermediate'],
                $skills['Redis']->id => ['proficiency' => 'intermediate'],
                $skills['Git']->id => ['proficiency' => 'advanced'],
                $skills['REST API']->id => ['proficiency' => 'advanced'],
            ]);
        }

        // Emma - Junior Developer
        $emma = $candidates->where('email', 'emma@example.com')->first();
        if ($emma && $emma->candidateProfile) {
            $emma->candidateProfile->skills()->attach([
                $skills['JavaScript']->id => ['proficiency' => 'intermediate'],
                $skills['React']->id => ['proficiency' => 'beginner'],
                $skills['HTML']->id ?? 1 => ['proficiency' => 'intermediate'],
                $skills['CSS']->id ?? 2 => ['proficiency' => 'intermediate'],
                $skills['Git']->id => ['proficiency' => 'beginner'],
            ]);
        }

        // David - Frontend Specialist
        $david = $candidates->where('email', 'david@example.com')->first();
        if ($david && $david->candidateProfile) {
            $david->candidateProfile->skills()->attach([
                $skills['JavaScript']->id => ['proficiency' => 'expert'],
                $skills['TypeScript']->id => ['proficiency' => 'advanced'],
                $skills['React']->id => ['proficiency' => 'expert'],
                $skills['Vue.js']->id => ['proficiency' => 'advanced'],
                $skills['Angular']->id => ['proficiency' => 'intermediate'],
                $skills['Node.js']->id => ['proficiency' => 'intermediate'],
                $skills['Git']->id => ['proficiency' => 'advanced'],
                $skills['GraphQL']->id => ['proficiency' => 'intermediate'],
            ]);
        }

        // Frank - DevOps Engineer
        $frank = $candidates->where('email', 'frank@example.com')->first();
        if ($frank && $frank->candidateProfile) {
            $frank->candidateProfile->skills()->attach([
                $skills['Docker']->id => ['proficiency' => 'expert'],
                $skills['Kubernetes']->id => ['proficiency' => 'advanced'],
                $skills['AWS']->id => ['proficiency' => 'expert'],
                $skills['CI/CD']->id => ['proficiency' => 'expert'],
                $skills['Git']->id => ['proficiency' => 'expert'],
                $skills['Python']->id => ['proficiency' => 'intermediate'],
                $skills['Bash']->id ?? 3 => ['proficiency' => 'advanced'],
                $skills['Terraform']->id ?? 4 => ['proficiency' => 'advanced'],
            ]);
        }

        $this->command->info('Skills assigned to candidates successfully!');
    }
}
