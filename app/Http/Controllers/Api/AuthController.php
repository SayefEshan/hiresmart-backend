<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());

            return (new AuthResource(
                $result['user'],
                $result['token'],
                $result['token_type'],
                $result['expires_in']
            ))->response()->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            return (new AuthResource(
                $result['user'],
                $result['token'],
                $result['token_type'],
                $result['expires_in']
            ))->response();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], $e->getCode() ?: Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Logout user
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh(): JsonResponse
    {
        try {
            $result = $this->authService->refresh();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token refresh failed',
                'error' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Get authenticated user
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($this->authService->me())
        ]);
    }
}
