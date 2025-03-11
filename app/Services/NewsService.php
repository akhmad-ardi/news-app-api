<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Lib\Utils;
use App\Models\News;
use App\Models\Picture;

class NewsService
{
    public function get_news(array $where)
    {
        return News::where($where)->get();
    }

    public function create($user_id, array $data)
    {
        try {
            $slug = Utils::slug($data['title']);

            $news_already_exist = News::where('slug', '=', $slug)->first();
            if ($news_already_exist) {
                throw new \Exception('news already exist', 400);
            }

            DB::transaction(function () use ($user_id, $data, $slug) {
                $thumbnail_path = $data['thumbnail']->store('thumbnails', 'public');

                $news = News::create([
                    'user_id' => $user_id,
                    'title' => $data['title'],
                    'slug' => $slug,
                    'excerpt' => Utils::excerpt($data['body']),
                    'body' => $data['body'],
                    'thumbnail' => $thumbnail_path
                ]);

                $pictures_path = [];
                foreach ($data['pictures'] as $file) {
                    $picture_path = $file->store('pictures', 'public');

                    $pictures_path[] = [
                        'news_id' => $news->id,
                        'name' => $picture_path,
                    ];
                }

                Picture::insert($pictures_path); // Batch insert lebih efisien
            });

            return [
                'message' => 'news created successfully',
                'status_code' => 201
            ];
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
                'status_code' => $e->getCode()
            ];
        } catch (\Throwable $th) {
            return [
                'message' => 'something error',
                'status_code' => 500,
            ];
        }
    }
}
