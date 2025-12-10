<?php

namespace Tests\Unit;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    /**
     * Test login request validation rules
     */
    public function test_login_request_requires_email_and_password(): void
    {
        $request = new LoginRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);

        $this->assertContains('required', $rules['email']);
        $this->assertContains('email', $rules['email']);
        $this->assertContains('required', $rules['password']);
    }

    /**
     * Test validation fails with empty data
     */
    public function test_validation_fails_with_empty_data(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /**
     * Test validation fails with invalid email format
     */
    public function test_validation_fails_with_invalid_email(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'not-an-email',
            'password' => 'password123',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /**
     * Test validation passes with valid data
     */
    public function test_validation_passes_with_valid_data(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'user@example.com',
            'password' => 'password123',
        ], $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Test login request is always authorized
     */
    public function test_login_request_is_authorized(): void
    {
        $request = new LoginRequest();
        
        $this->assertTrue($request->authorize());
    }
}
