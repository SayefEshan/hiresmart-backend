<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'bio',
        'location',
        'preferred_location',
        'min_salary',
        'max_salary',
        'resume_url',
        'experience_years',
    ];

    protected $casts = [
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
        'experience_years' => 'integer',
    ];

    /**
     * Get the user that owns the candidate profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skills for this candidate.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'candidate_skills')
            ->withPivot('proficiency')
            ->withTimestamps();
    }
}
