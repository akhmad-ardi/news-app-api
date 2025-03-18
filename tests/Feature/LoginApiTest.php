<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function registration_user()
    {
        $data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $response = $this->postJson('/api/register', $data);

        return $response->json('token');
    }

    public function test_login_fail_all_input(): void
    {
        $data = [
            'email' => '',
            'password' => ''
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_fail_email(): void
    {
        $data = [
            'email' => '',
            'password' => '123456'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_login_fail_password(): void
    {
        $data = [
            'email' => 'test@gmail.com',
            'password' => ''
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_login_fail_invalid_input()
    {
        $data = [
            'email' => 'test@gmail.com',
            'password' => '6543211111111'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'email or password is invalid']);
    }

    public function test_login_success()
    {
        $this->registration_user();

        $data = [
            'email' => 'test@gmail.com',
            'password' => '123456',
        ];

        $response = $this->postJson('/api/login', [
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'login successful']);
        $response->assertSee(['token']);
    }

    public function test_logout_fail()
    {
        $response = $this->deleteJson('/api/logout', [
            'Authorization' => 'Bearer '
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_logout_success()
    {
        $token = $this->registration_user();

        $response = $this->deleteJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'logout successful']);
    }
}
