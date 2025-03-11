<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private $data = [
        'name' => 'test',
        'email' => 'test@gmail.com',
        'password' => '123456',
        'password_confirmation' => '123456'
    ];

    private function register_user()
    {
        $response = $this->postJson('/api/register', $this->data);

        return $response->json('token');
    }

    public function test_get_fail_user(): void
    {
        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer '
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_get_user(): void
    {
        $token = $this->register_user();

        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['name', 'email']
        ]);
    }

    public function test_get_user_news_success()
    {
        $token = $this->register_user();

        $response = $this->getJson('/api/user/news', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
        // $response->assertJsonStructure(['data']);
    }
}
