<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'cover_letter' => $this->cover_letter,
            'status' => $this->status,
            'applied_at' => $this->applied_at,
            'job_listing' => $this->whenLoaded('jobListing', function () {
                return [
                    'id' => $this->jobListing->id,
                    'title' => $this->jobListing->title,
                    'location' => $this->jobListing->location,
                    'employment_type' => $this->jobListing->employment_type,
                    'employer' => [
                        'name' => $this->jobListing->employer->name,
                        'company' => $this->jobListing->employer->employerProfile?->company_name,
                    ],
                ];
            }),
            'candidate' => $this->whenLoaded('candidate', function () {
                return [
                    'id' => $this->candidate->id,
                    'name' => $this->candidate->name,
                    'email' => $this->candidate->email,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
