<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can view users list
     */
    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    /**
     * Test admin can create a new user with valid data
     */
    public function test_admin_can_create_user_with_valid_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'perusahaan',
            'status' => 'active',
            'notes' => 'Test notes',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User berhasil dibuat.');

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'perusahaan',
            'status' => 'active',
        ]);
    }

    /**
     * Test user creation fails with invalid email
     */
    public function test_user_creation_fails_with_invalid_email(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $userData = [
            'name' => 'New User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'perusahaan',
            'status' => 'active',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test user creation fails with duplicate email
     */
    public function test_user_creation_fails_with_duplicate_email(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'perusahaan',
            'status' => 'active',
        ];

        $response = $this->actingAs($admin)->post(route('users.store'), $userData);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test admin can update user
     */
    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'perusahaan',
            'status' => 'active',
        ];

        $response = $this->actingAs($admin)->put(route('users.update', $user), $updateData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test admin cannot delete active user
     */
    public function test_admin_cannot_delete_active_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['status' => 'active']);

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error', 'User hanya dapat dihapus jika statusnya non-aktif.');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * Test admin can delete inactive user
     */
    public function test_admin_can_delete_inactive_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['status' => 'inactive']);

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User berhasil dihapus.');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Test admin cannot delete themselves
     */
    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'inactive']);

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error', 'Tidak dapat menghapus akun sendiri.');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /**
     * Test admin can update user password
     */
    public function test_admin_can_update_user_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('users.password.update', $user), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('users.show', $user));
        $response->assertSessionHas('success', 'Password berhasil diperbarui.');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test password update validation
     */
    public function test_password_update_requires_confirmation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('users.password.update', $user), [
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
