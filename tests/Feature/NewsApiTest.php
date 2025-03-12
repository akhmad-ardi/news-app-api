<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Lib\Utils;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    private $data_user = [
        'name' => 'test',
        'email' => 'test@gmail.com',
        'password' => '123456',
        'password_confirmation' => '123456'
    ];

    private $data_news = [
        'title' => 'test',
        'body' => 'test',
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

    private function request_create_news(array $data, string $token = "")
    {
        if (strlen($token) == 0) {
            $token = $this->register_user();
        }

        $file_images = $this->create_file_images();

        $response = $this->post(
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

        return [
            'response' => $response,
            'token_user' => $token
        ];
    }

    /*-----------------------------------------------------------*/

    /* ---Create News--- */

    public function test_create_news_fail_all_input()
    {
        $news = $this->request_create_news([]);

        $news['response']->assertStatus(422);
        $news['response']->assertJsonStructure(['errors']);
    }

    public function test_create_news_already_exist()
    {
        $this->request_create_news($this->data_news);

        $news = $this->request_create_news($this->data_news);

        $news['response']->assertStatus(422);
        $news['response']->assertJsonStructure(['errors']);
    }

    public function test_create_news_success()
    {
        $news = $this->request_create_news($this->data_news);

        $news['response']->assertStatus(201);
        $news['response']->assertJsonStructure(['message']);
    }

    /* ---Get News--- */

    public function test_get_news_success()
    {
        $response = $this->getJson('/api/news');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /* ---Update News--- */

    public function test_update_news_fail_all_input()
    {
        $news = $this->request_create_news($this->data_news);

        $slug = Utils::slug($this->data_news['title']);

        $response = $this->putJson(
            '/api/news/' . $slug,
            [],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['title', 'body']]);
    }

    public function test_update_news_already_exist()
    {
        $news = $this->request_create_news($this->data_news);

        $this->request_create_news([
            'title' => 'test 1',
            'body' => 'test 1'
        ], $news['token_user']);

        $slug = Utils::slug($this->data_news['title']);

        $response = $this->putJson(
            '/api/news/' . $slug,
            [
                'title' => 'test 1',
                'body' => 'test 1'
            ],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors' => ['title']]);
    }

    public function test_update_news_success()
    {
        $news = $this->request_create_news($this->data_news);

        $slug = Utils::slug($this->data_news['title']);

        $response = $this->putJson(
            '/api/news/' . $slug,
            [
                'title' => 'test 1',
                'body' => 'test 1'
            ],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['message' => 'news updated']);
    }

    /* ---Update thumbnail News--- */

    public function test_update_thumbnail_news_fail_all_input()
    {
        $news = $this->request_create_news($this->data_news);

        $slug = Utils::slug($this->data_news['title']);

        $response = $this->putJson(
            '/api/news/thumbnail/' . $slug,
            [],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_update_thumbnail_news_success()
    {
        $news = $this->request_create_news($this->data_news);

        $slug = Utils::slug($this->data_news['title']);

        $images = $this->create_file_images();

        $response = $this->putJson(
            '/api/news/thumbnail/' . $slug,
            [
                'thumbnail' => $images['thumbnail']
            ],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['message' => 'thumbnail news updated']);
    }

    /* ---Update Pictures News--- */
    public function test_update_pictures_fail_all_input()
    {
        $news = $this->request_create_news($this->data_news);

        $slug = Utils::slug($this->data_news['title']);

        $response = $this->putJson(
            '/api/news/pictures/' . $slug,
            [],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_update_pictures_success()
    {
        $news = $this->request_create_news($this->data_news);

        $slug = Utils::slug($this->data_news['title']);

        $images = $this->create_file_images();

        $response = $this->putJson(
            '/api/news/pictures/' . $slug,
            [
                'pictures' => $images['pictures']
            ],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['message' => 'pictures updated successfuly']);
    }

    /* ---Delete News--- */

    public function test_delete_news_not_found()
    {
        $token = $this->register_user();

        $response = $this->deleteJson('/api/news/test', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'news not found']);
    }

    public function test_delete_news_success()
    {
        $news = $this->request_create_news($this->data_news);

        $response = $this->deleteJson(
            '/api/news/' . Utils::slug($this->data_news['title']),
            [],
            [
                'Authorization' => 'Bearer ' . $news['token_user']
            ]
        );

        $response->assertStatus(200);
    }
}
