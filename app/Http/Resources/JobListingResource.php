<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'employment_type' => $this->employment_type,
            'salary_range' => [
                'min' => $this->min_salary,
                'max' => $this->max_salary,
                'currency' => 'USD',
            ],
            'experience_required' => $this->experience_required,
            'is_active' => $this->is_active,
            'is_archived' => $this->is_archived,
            'employer' => $this->whenLoaded('employer', function () {
                return [
                    'id' => $this->employer->id,
                    'name' => $this->employer->name,
                    'company' => $this->employer->employerProfile?->company_name,
                ];
            }),
            'applications_count' => $this->when($request->user()?->id === $this->user_id, function () {
                return [
                    'total' => $this->applications_count ?? 0,
                    'pending' => $this->pending_applications_count ?? 0,
                    'reviewed' => $this->reviewed_applications_count ?? 0,
                    'shortlisted' => $this->shortlisted_applications_count ?? 0,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
