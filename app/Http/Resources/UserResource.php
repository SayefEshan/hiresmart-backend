<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name');
            }),
            'is_verified' => $this->is_verified,
            'employer_profile' => $this->when(
                $this->relationLoaded('employerProfile') && $this->employerProfile,
                function () {
                    return [
                        'company_name' => $this->employerProfile->company_name,
                        'industry' => $this->employerProfile->industry,
                        'location' => $this->employerProfile->location,
                        'website' => $this->employerProfile->website,
                    ];
                }
            ),
            'candidate_profile' => $this->when(
                $this->relationLoaded('candidateProfile') && $this->candidateProfile,
                function () {
                    return [
                        'phone' => $this->candidateProfile->phone,
                        'location' => $this->candidateProfile->location,
                        'experience_years' => $this->candidateProfile->experience_years,
                        'resume_url' => $this->candidateProfile->resume_url,
                    ];
                }
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
