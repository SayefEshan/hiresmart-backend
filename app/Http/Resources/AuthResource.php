<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    private string $token;
    private string $tokenType;
    private int $expiresIn;

    public function __construct($resource, string $token, string $tokenType = 'bearer', int $expiresIn = 3600)
    {
        parent::__construct($resource);
        $this->token = $token;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'roles' => $this->roles->pluck('name'),
                'is_verified' => $this->is_verified,
                'created_at' => $this->created_at,
            ],
            'access_token' => $this->token,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}
