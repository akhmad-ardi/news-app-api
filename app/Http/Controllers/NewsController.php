<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Http\Requests\NewsUpdateThumbnailRequest;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private NewsService $news_service;

    public function __construct(NewsService $news_service)
    {
        $this->news_service = $news_service;
    }

    public function create(NewsCreateRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();

        $process_create_news = $this->news_service->create($user->id, $data);

        return response()
            ->json([
                'message' => $process_create_news['message'],
            ], $process_create_news['status_code']);
    }

    public function get_news(Request $request)
    {
        $slug = $request->query('slug');
        if (isset($slug) && strlen($slug) != 0) {
            $news = $this->news_service->get_news([
                ['slug', '=', $slug]
            ]);

            return response()
                ->json(['data' => $news], 200);
        }

        $news = $this->news_service->get_news([]);
        return response()
            ->json(['data' => $news], 200);
    }

    public function update_news(NewsUpdateRequest $request, string $slug)
    {
        $data = $request->validated();

        $user = $request->user();

        $process_update_news = $this->news_service->update_news(
            $user->id,
            $slug,
            $data
        );

        return response()
            ->json([
                'message' => $process_update_news['message']
            ], $process_update_news['status_code']);
    }

    public function update_thumbnail_news(NewsUpdateThumbnailRequest $request, string $slug)
    {
        $user = $request->user();

        $data = $request->validated();

        $process_update_thumbnail_news = $this->news_service->update_thumbnail_news(
            $user->id,
            $slug,
            $data
        );

        return response()
            ->json([
                'message' => $process_update_thumbnail_news['message']
            ], $process_update_thumbnail_news['status_code']);
    }

    public function update_pictures_news()
    {
    }

    public function delete_news(Request $request, string $slug)
    {
        $user = $request->user();

        $process_delete_news = $this->news_service->delete($user->id, $slug);

        return response()
            ->json([
                'message' => $process_delete_news['message'],
            ], $process_delete_news['status_code']);
    }
}
