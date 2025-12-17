<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JenisLimbahServiceClient
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.jenis_limbah_service.url', 'http://localhost:8001');
    }

    /**
     * Get list of jenis limbah.
     *
     * @param array $filters
     * @param string|null $correlationId
     * @return array
     */
    public function getJenisLimbahs(array $filters = [], ?string $correlationId = null): array
    {
        Log::info('Calling Jenis Limbah Service - Get List', [
            'correlation_id' => $correlationId,
            'filters' => $filters,
            'service' => 'jenis-limbah-service',
        ]);

        try {
            $response = Http::withHeaders([
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/api/jenis-limbah", $filters);

            if ($response->successful()) {
                Log::info('Jenis Limbah Service list fetched successfully', [
                    'correlation_id' => $correlationId,
                    'status' => $response->status(),
                ]);

                return [
                    'success' => true,
                    'data' => $response->json(),
                    'status' => $response->status(),
                ];
            }

            Log::warning('Jenis Limbah Service list fetch failed', [
                'correlation_id' => $correlationId,
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Error calling Jenis Limbah Service', [
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
     * Get single jenis limbah detail.
     *
     * @param int $id
     * @param string|null $correlationId
     * @return array
     */
    public function getJenisLimbah(int $id, ?string $correlationId = null): array
    {
        Log::info('Calling Jenis Limbah Service - Get Detail', [
            'correlation_id' => $correlationId,
            'id' => $id,
            'service' => 'jenis-limbah-service',
        ]);

        try {
            $response = Http::withHeaders([
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/api/jenis-limbah/{$id}");

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
            Log::error('Error calling Jenis Limbah Service get detail', [
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
     * Create new jenis limbah.
     *
     * @param array $data
     * @param string|null $correlationId
     * @return array
     */
    public function createJenisLimbah(array $data, ?string $correlationId = null): array
    {
        Log::info('Calling Jenis Limbah Service - Create', [
            'correlation_id' => $correlationId,
            'service' => 'jenis-limbah-service',
        ]);

        try {
            $response = Http::withHeaders([
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/api/jenis-limbah", $data);

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
            Log::error('Error calling Jenis Limbah Service create', [
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
     * Update jenis limbah.
     *
     * @param int $id
     * @param array $data
     * @param string|null $correlationId
     * @return array
     */
    public function updateJenisLimbah(int $id, array $data, ?string $correlationId = null): array
    {
        Log::info('Calling Jenis Limbah Service - Update', [
            'correlation_id' => $correlationId,
            'id' => $id,
            'service' => 'jenis-limbah-service',
        ]);

        try {
            $response = Http::withHeaders([
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->put("{$this->baseUrl}/api/jenis-limbah/{$id}", $data);

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
            Log::error('Error calling Jenis Limbah Service update', [
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
     * Delete jenis limbah.
     *
     * @param int $id
     * @param string|null $correlationId
     * @return array
     */
    public function deleteJenisLimbah(int $id, ?string $correlationId = null): array
    {
        Log::info('Calling Jenis Limbah Service - Delete', [
            'correlation_id' => $correlationId,
            'id' => $id,
            'service' => 'jenis-limbah-service',
        ]);

        try {
            $response = Http::withHeaders([
                'X-Correlation-ID' => $correlationId,
                'Accept' => 'application/json',
            ])->delete("{$this->baseUrl}/api/jenis-limbah/{$id}");

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
            Log::error('Error calling Jenis Limbah Service delete', [
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
