<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_listing_id',
        'user_id',
        'match_score',
        'match_criteria',
        'notification_sent',
    ];

    protected $casts = [
        'match_score' => 'decimal:2',
        'match_criteria' => 'array',
        'notification_sent' => 'boolean',
    ];

    /**
     * Get the job listing for this match.
     */
    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class);
    }

    /**
     * Get the candidate user for this match.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
