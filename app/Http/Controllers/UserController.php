<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;

class UserController extends Controller
{
    private NewsService $news_service;

    public function __construct(NewsService $news_service)
    {
        $this->news_service = $news_service;
    }

    public function get_user(Request $request)
    {
        return response()
            ->json([
                'data' => $request->user()
            ], 200);
    }

    public function get_user_news(Request $request)
    {
        $user = $request->user();

        $slug = $request->query('slug');
        if (isset($slug) && !empty($slug)) {
            $news = $this->news_service->get_news([
                ['user_id', '=', $user->id],
                ['slug', '=', $slug]
            ]);

            return response()
                ->json(['data' => $news], 200);
        }

        $news = $this->news_service->get_news([
            ['user_id', '=', $user->id]
        ]);

        return response()
            ->json(['data' => $news], 200);
    }
}
