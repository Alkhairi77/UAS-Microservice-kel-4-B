<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JenisSampah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test; // <- Perbaiki kapitalisasi

class JenisSampahTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    #[Test]
    public function can_create_jenis_sampah()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.jenis-sampah.store'), [
                'nama' => 'Sampah Organik Baru',
                'kategori' => array_key_first(JenisSampah::KATEGORI),
                'harga_per_kg' => 5000,
                'deskripsi' => 'Deskripsi sampah organik',
                'status' => 'aktif',
            ]);

        $response->assertRedirect(route('admin.jenis-sampah.index'));

        $this->assertDatabaseHas('jenis_sampahs', [
            'nama' => 'Sampah Organik Baru',
        ]);
    }

    #[Test]
    public function validates_required_fields_on_create()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.jenis-sampah.store'), []);

        $response->assertSessionHasErrors([
            'nama', 'kategori', 'harga_per_kg', 'status'
        ]);
    }

    #[Test]
    public function can_update_jenis_sampah()
    {
        $item = JenisSampah::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.jenis-sampah.update', $item->id), [
                'nama' => 'Sampah Baru',
                'kategori' => array_key_first(JenisSampah::KATEGORI),
                'harga_per_kg' => 8000,
                'deskripsi' => 'Deskripsi baru',
                'status' => 'aktif',
            ]);

        $response->assertRedirect(route('admin.jenis-sampah.index'));

        $this->assertDatabaseHas('jenis_sampahs', [
            'id' => $item->id,
            'nama' => 'Sampah Baru',
        ]);
    }

    #[Test]
    public function can_delete_jenis_sampah()
    {
        $item = JenisSampah::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.jenis-sampah.destroy', $item->id));

        $response->assertRedirect(route('admin.jenis-sampah.index'));

        $this->assertDatabaseMissing('jenis_sampahs', [
            'id' => $item->id
        ]);
    }
}
