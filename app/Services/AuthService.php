<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Register a new user
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_verified' => false,
            ]);

            // Assign role
            $user->assignRole($data['role']);

            // Create profile based on role
            if ($data['role'] === 'employer') {
                $user->employerProfile()->create([
                    'company_name' => $data['company_name'] ?? $data['name'],
                ]);
            } elseif ($data['role'] === 'candidate') {
                $user->candidateProfile()->create();
            }

            $token = JWTAuth::fromUser($user);

            return [
                'user' => $user->load('roles'),
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ];
        });
    }

    /**
     * Login user
     */
    public function login(array $credentials): array
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user = auth()->user();

        return [
            'user' => $user->load('roles'),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Refresh token
     */
    public function refresh(): array
    {
        $token = JWTAuth::refresh();

        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Get authenticated user
     */
    public function me(): User
    {
        return auth()->user()->load(['roles', 'employerProfile', 'candidateProfile']);
    }
}
