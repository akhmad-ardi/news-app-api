<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsCreateRequest;
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
}
