<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_fail_all_input(): void
    {
        $register_data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => ''
        ];

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_register_fail_name(): void
    {
        $register_data = [
            'name' => '',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_register_fail_email()
    {
        $register_data = [
            'name' => 'test',
            'email' => '',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_register_fail_password()
    {
        $register_data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '',
            'password_confirmation' => '123456'
        ];

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_register_fail_password_confirmation()
    {
        $register_data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => ''
        ];

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password_confirmation']);
    }

    public function test_register_not_match_password_confirmation()
    {
        $register_data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => '654321123123'
        ];

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_register_user_already_exist()
    {
        $register_data = [
            'name' => 'user already exist',
            'email' => 'user_already_exist@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson('/api/register', $register_data);

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(422);
        $response->assertSee(['user already exist']);
    }

    public function test_register_success()
    {
        $register_data = [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $response = $this->postJson('/api/register', $register_data);

        $response->assertStatus(201);
        $response->assertSee(['registration successful', 'token']);
    }
}
