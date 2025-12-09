<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation with valid data
     */
    public function test_user_can_be_created_with_valid_data(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'perusahaan',
            'status' => 'active',
        ];

        $user = User::create($userData);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'perusahaan',
            'status' => 'active',
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Test user role checking methods
     */
    public function test_user_role_checking_methods(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $perusahaanUser = User::factory()->create(['role' => 'perusahaan']);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($adminUser->isPerusahaan());

        $this->assertTrue($perusahaanUser->isPerusahaan());
        $this->assertFalse($perusahaanUser->isAdmin());
    }

    /**
     * Test user email must be unique
     */
    public function test_user_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'duplicate@example.com']);

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        User::create([
            'name' => 'Another User',
            'email' => 'duplicate@example.com',
            'password' => bcrypt('password123'),
            'role' => 'perusahaan',
            'status' => 'active',
        ]);
    }

    /**
     * Test user status can be active or inactive
     */
    public function test_user_status_can_be_set(): void
    {
        $activeUser = User::factory()->create(['status' => 'active']);
        $inactiveUser = User::factory()->create(['status' => 'inactive']);

        $this->assertEquals('active', $activeUser->status);
        $this->assertEquals('inactive', $inactiveUser->status);
    }

    /**
     * Test user can update last login timestamp
     */
    public function test_user_can_update_last_login(): void
    {
        $user = User::factory()->create();
        
        $this->assertNull($user->last_login_at);
        
        $user->updateLastLogin();
        $user->refresh();
        
        $this->assertNotNull($user->last_login_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->last_login_at);
    }

    /**
     * Test user password is hashed
     */
    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('plainpassword'),
        ]);

        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(\Hash::check('plainpassword', $user->password));
    }
}
