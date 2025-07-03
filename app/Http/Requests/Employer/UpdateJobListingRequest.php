<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('edit jobs');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'min:50'],
            'location' => ['sometimes', 'string', 'max:255'],
            'employment_type' => ['sometimes', Rule::in(['full-time', 'part-time', 'contract', 'internship'])],
            'min_salary' => ['nullable', 'numeric', 'min:0'],
            'max_salary' => ['nullable', 'numeric', 'gt:min_salary'],
            'experience_required' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
