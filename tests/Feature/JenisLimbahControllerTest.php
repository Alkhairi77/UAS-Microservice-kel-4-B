<?php

namespace Tests\Feature;

use App\Models\JenisLimbah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisLimbahControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test can get all jenis limbah with correlation ID
     */
    public function test_can_get_all_jenis_limbah_with_correlation_id(): void
    {
        // Create test data
        JenisLimbah::factory()->count(3)->create();

        $correlationId = 'test-correlation-123';

        $response = $this->withHeaders([
            'X-Correlation-ID' => $correlationId,
        ])->getJson('/api/jenis-limbah');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'kode_limbah',
                        'nama_limbah',
                        'kategori',
                        'status',
                    ]
                ],
                'correlation_id',
            ])
            ->assertJson([
                'success' => true,
                'correlation_id' => $correlationId,
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test can create new jenis limbah with validation
     */
    public function test_can_create_jenis_limbah_with_valid_data(): void
    {
        $data = [
            'kode_limbah' => 'TEST-001',
            'nama_limbah' => 'Limbah Test',
            'deskripsi' => 'Deskripsi test limbah',
            'kategori' => 'B3',
            'satuan_default' => 'kg',
            'status' => 'aktif',
        ];

        $response = $this->postJson('/api/jenis-limbah', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Jenis limbah created successfully',
            ]);

        $this->assertDatabaseHas('jenis_limbahs', [
            'kode_limbah' => 'TEST-001',
            'nama_limbah' => 'Limbah Test',
            'kategori' => 'B3',
        ]);
    }

    /**
     * Test validation fails when required fields are missing
     */
    public function test_validation_fails_when_required_fields_missing(): void
    {
        $data = [
            'nama_limbah' => 'Limbah Test',
            // kode_limbah and kategori are missing
        ];

        $response = $this->postJson('/api/jenis-limbah', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['kode_limbah', 'kategori']);
    }

    /**
     * Test can update jenis limbah
     */
    public function test_can_update_jenis_limbah(): void
    {
        $jenisLimbah = JenisLimbah::factory()->create([
            'kode_limbah' => 'OLD-001',
            'nama_limbah' => 'Old Name',
        ]);

        $updateData = [
            'kode_limbah' => 'OLD-001', // Keep same code
            'nama_limbah' => 'Updated Name',
            'kategori' => 'Non-B3',
            'satuan_default' => 'kg',
            'status' => 'aktif',
        ];

        $response = $this->putJson("/api/jenis-limbah/{$jenisLimbah->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Jenis limbah updated successfully',
            ]);

        $this->assertDatabaseHas('jenis_limbahs', [
            'id' => $jenisLimbah->id,
            'nama_limbah' => 'Updated Name',
        ]);
    }

    /**
     * Test can delete jenis limbah
     */
    public function test_can_delete_jenis_limbah(): void
    {
        $jenisLimbah = JenisLimbah::factory()->create();

        $response = $this->deleteJson("/api/jenis-limbah/{$jenisLimbah->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Jenis limbah deleted successfully',
            ]);

        $this->assertDatabaseMissing('jenis_limbahs', [
            'id' => $jenisLimbah->id,
        ]);
    }

    /**
     * Test error handling when jenis limbah not found
     */
    public function test_returns_404_when_jenis_limbah_not_found(): void
    {
        $response = $this->getJson('/api/jenis-limbah/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Jenis limbah not found',
            ]);
    }

    /**
     * Test can filter jenis limbah by kategori
     */
    public function test_can_filter_by_kategori(): void
    {
        JenisLimbah::factory()->create(['kategori' => 'B3']);
        JenisLimbah::factory()->create(['kategori' => 'Non-B3']);
        JenisLimbah::factory()->create(['kategori' => 'B3']);

        $response = $this->getJson('/api/jenis-limbah?kategori=B3');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    /**
     * Test correlation ID is returned in response
     */
    public function test_correlation_id_is_returned_in_response(): void
    {
        $response = $this->getJson('/api/jenis-limbah');

        $response->assertStatus(200)
            ->assertJsonStructure(['correlation_id']);
    }
}
