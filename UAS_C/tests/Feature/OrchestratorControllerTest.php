<?php

namespace Tests\Feature;

use App\Services\UserServiceClient;
use App\Services\JenisLimbahServiceClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class OrchestratorControllerTest extends TestCase
{
    // Don't use RefreshDatabase for orchestrator tests with mocked services

    /**
     * Test login with limbah data success
     */
    public function test_login_with_limbah_data_success(): void
    {
        $cid = 'test-cid-success-123';

        Http::fake([
            'http://localhost:8000/api/login' => Http::response([
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ],
                'token' => 'fake-token-123',
            ], 200),
            'http://localhost:8000/api/profile' => Http::response([
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ],
            ], 200),
            'http://localhost:8001/api/jenis-limbah*' => Http::response([
                'success' => true,
                'data' => [
                    'data' => [
                        ['id' => 10, 'kode_limbah' => 'K-001', 'nama_limbah' => 'Limbah A'],
                        ['id' => 11, 'kode_limbah' => 'K-002', 'nama_limbah' => 'Limbah B'],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->withHeaders([
            'X-Correlation-ID' => $cid,
        ])->postJson('/api/orchestrator/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertHeader('X-Correlation-ID', $cid)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful with limbah data',
                'correlation_id' => $cid,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                    'jenis_limbah',
                ],
                'correlation_id',
            ]);
    }

    /**
     * Test login validation fails
     */
    public function test_login_validation_fails(): void
    {
        $response = $this->postJson('/api/orchestrator/login', [
            'email' => 'invalid-email',
            // password missing
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Test login fails when user service returns error
     */
    public function test_login_fails_when_user_service_error(): void
    {
        $userServiceMock = Mockery::mock(UserServiceClient::class);
        $userServiceMock->shouldReceive('login')
            ->once()
            ->andReturn([
                'success' => false,
                'error' => 'Invalid credentials',
                'status' => 401,
            ]);

        $this->app->instance(UserServiceClient::class, $userServiceMock);

        $response = $this->postJson('/api/orchestrator/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Login failed',
            ]);
    }

    /**
     * Test correlation ID is forwarded and returned
     */
    public function test_correlation_id_is_forwarded_and_returned(): void
    {
        $cid = 'cid-forward-456';

        Http::fake([
            'http://localhost:8000/api/login' => Http::response([
                'user' => ['id' => 1, 'name' => 'User', 'email' => 'u@example.com'],
                'token' => 't-123',
            ], 200),
            'http://localhost:8000/api/profile' => Http::response([
                'user' => ['id' => 1, 'name' => 'User', 'email' => 'u@example.com'],
            ], 200),
            'http://localhost:8001/api/jenis-limbah*' => Http::response([
                'success' => true,
                'data' => [
                    'data' => [],
                ],
            ], 200),
        ]);

        $response = $this->withHeaders([
            'X-Correlation-ID' => $cid,
        ])->postJson('/api/orchestrator/login', [
            'email' => 'u@example.com',
            'password' => 'secret',
        ]);

        // Assert the response carries the correlation ID in header and body
        $response->assertStatus(200)
            ->assertHeader('X-Correlation-ID', $cid)
            ->assertJsonPath('correlation_id', $cid);

        // Assert all outbound HTTP calls forwarded the same correlation ID
        Http::assertSent(function ($request) use ($cid) {
            return $request->hasHeader('X-Correlation-ID')
                && in_array($cid, (array) $request->header('X-Correlation-ID'));
        });
    }

    /**
     * Test get user with limbah requires authentication
     */
    public function test_get_user_with_limbah_requires_authentication(): void
    {
        $response = $this->getJson('/api/orchestrator/user-with-limbah');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Authorization token required',
            ]);
    }

    /**
     * Test error handling is consistent
     */
    public function test_error_handling_is_consistent(): void
    {
        $userServiceMock = Mockery::mock(UserServiceClient::class);
        $userServiceMock->shouldReceive('login')
            ->once()
            ->andThrow(new \Exception('Service unavailable'));

        $this->app->instance(UserServiceClient::class, $userServiceMock);

        $response = $this->postJson('/api/orchestrator/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(500)
            ->assertJsonStructure([
                'success',
                'message',
                'error',
                'correlation_id',
            ])
            ->assertJson([
                'success' => false,
            ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
