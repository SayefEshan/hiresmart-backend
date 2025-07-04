<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'employment_type',
        'min_salary',
        'max_salary',
        'experience_required',
        'is_active',
        'is_archived',
        'archived_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
    ];

    /**
     * Get the employer that owns the job listing.
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the applications for the job listing.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_listing_id');
    }

    /**
     * Scope a query to only include active job listings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('is_archived', false);
    }

    /**
     * Scope a query to only include archived job listings.
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('is_archived', true);
    }

    /**
     * Check if the given user owns this job listing
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Get applications count by status
     */
    public function getApplicationsCountByStatus(): array
    {
        return [
            'total' => $this->applications()->count(),
            'pending' => $this->applications()->where('status', 'pending')->count(),
            'reviewed' => $this->applications()->where('status', 'reviewed')->count(),
            'shortlisted' => $this->applications()->where('status', 'shortlisted')->count(),
            'rejected' => $this->applications()->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Get the skills required for this job listing.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skills')
            ->withPivot('is_required')
            ->withTimestamps();
    }

    /**
     * Get the job matches for this listing.
     */
    public function jobMatches()
    {
        return $this->hasMany(JobMatch::class);
    }
}
