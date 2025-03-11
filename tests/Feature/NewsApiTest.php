<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    private $data_user = [
        'name' => 'test',
        'email' => 'test@gmail.com',
        'password' => '123456',
        'password_confirmation' => '123456'
    ];

    private function register_user()
    {
        $response = $this->postJson('/api/register', $this->data_user);

        return $response->json('token');
    }

    private function create_file_images()
    {
        Storage::fake('public');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');
        $pictures = [
            UploadedFile::fake()->image('picture-1.jpg'),
            UploadedFile::fake()->image('picture-2.jpg'),
            UploadedFile::fake()->image('picture-3.jpg'),
        ];

        return ['thumbnail' => $thumbnail, 'pictures' => $pictures];
    }

    private function request_create_news(array $data)
    {
        $token = $this->register_user();

        $file_images = $this->create_file_images();

        return $this->post(
            '/api/news',
            array_merge($data, [
                'thumbnail' => $file_images['thumbnail'],
                'pictures' => $file_images['pictures']
            ]),
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        );
    }

    public function test_create_news_fail_all_input(): void
    {
        $response = $this->request_create_news([]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_create_news_already_exist()
    {
        $this->request_create_news([
            'title' => 'test',
            'slug' => 'test',
            'excerpt' => 'test',
            'body' => 'test',

        ]);

        $response = $this->request_create_news([
            'title' => 'test',
            'slug' => 'test',
            'excerpt' => 'test',
            'body' => 'test',
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure(['message']);
    }

    public function test_create_news_success()
    {
        $response = $this->request_create_news([
            'title' => 'test',
            'slug' => 'test',
            'excerpt' => 'test',
            'body' => 'test',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message']);
    }

    public function test_get_news_success()
    {
        $response = $this->getJson('/api/news');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }
}
