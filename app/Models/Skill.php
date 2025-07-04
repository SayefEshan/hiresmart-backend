<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
    ];

    /**
     * Get the job listings that require this skill.
     */
    public function jobListings(): BelongsToMany
    {
        return $this->belongsToMany(JobListing::class, 'job_skills')
            ->withPivot('is_required')
            ->withTimestamps();
    }

    /**
     * Get the candidates that have this skill.
     */
    public function candidateProfiles(): BelongsToMany
    {
        return $this->belongsToMany(CandidateProfile::class, 'candidate_skills')
            ->withPivot('proficiency')
            ->withTimestamps();
    }
}
