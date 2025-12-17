<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserServiceClient
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.user_service.url', 'http://localhost:8000');
    }

    /**
     * Login user and get authentication token.
     *
     * @param string $email
     * @param string $password
     * @param string|null $correlationId
     * @return array
     */
    public function login(string $email, string $password, ?string $correlationId = null): array
    {
        Log::info('Calling User Service - Login', [
            'correlation_id' => $correlationId,
            'email' => $email,
            'service' => 'user-service',
        ]);

        try {
            $response = Http::withHeaders([
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/api/login", [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                Log::info('User Service login successful', [
                    'correlation_id' => $correlationId,
                    'status' => $response->status(),
                ]);

                return [
                    'success' => true,
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            Log::warning('User Service login failed', [
                'correlation_id' => $correlationId,
                'status' => $response->status(),
                'error' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Error calling User Service login', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Get user profile information.
     *
     * @param string $token
     * @param string|null $correlationId
     * @return array
     */
    public function getProfile(string $token, ?string $correlationId = null): array
    {
        Log::info('Calling User Service - Get Profile', [
            'correlation_id' => $correlationId,
            'service' => 'user-service',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/api/profile");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Error calling User Service get profile', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Register new user.
     *
     * @param array $data
     * @param string|null $correlationId
     * @return array
     */
    public function register(array $data, ?string $correlationId = null): array
    {
        Log::info('Calling User Service - Register', [
            'correlation_id' => $correlationId,
            'service' => 'user-service',
        ]);

        try {
            $response = Http::withHeaders([
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/api/register", $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Error calling User Service register', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
