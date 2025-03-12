<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        } catch (\Throwable $th) {
            return [
                'message' => 'something error',
                'status_code' => 500,
            ];
        }
    }

    public function update_news($user_id, $slug, array $data)
    {
        try {
            $news = News::where([
                ['user_id', '=', $user_id],
                ['slug', '=', $slug]
            ])->first();

            $news->title = $data['title'];
            $news->slug = Utils::slug($data['title']);
            $news->excerpt = Utils::excerpt($data['body']);
            $news->body = $data['body'];

            $news->save();

            return ['message' => 'news updated', 'status_code' => 200];
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return ['message' => 'something error', 'status_code' => 500];
        }
    }

    public function update_thumbnail_news($user_id, $slug, array $data)
    {
        try {
            $news = News::where([
                ['user_id', '=', $user_id],
                ['slug', '=', $slug]
            ])->first();

            $new_thumbnail_path = $data['thumbnail']->store('thumbnails', 'public');
            Storage::disk('public')->delete('thumbnail' . $news->thumbnail);

            $news->thumbnail = $new_thumbnail_path;

            $news->save();

            return [
                'message' => 'thumbnail news updated',
                'status_code' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'message' => 'something error',
                'status_code' => 500
            ];
        }
    }

    public function delete($user_id, string $slug)
    {
        try {
            $news = News::where([
                ['user_id', '=', $user_id],
                ['slug', '=', $slug]
            ])->first();
            if (!$news) {
                throw new \Exception('news not found', 404);
            }

            $pictures = Picture::where('news_id', '=', $news->id)->get();

            $pictures_path = [];
            foreach ($pictures as $picture) {
                $pictures_path[] = 'pictures/' . $picture->name;
            }

            Storage::disk('public')->delete('thumbnail' . $news->thumbnail);
            Storage::disk('public')->delete($pictures_path);

            $picture->delete();
            $news->delete();

            return [
                'message' => 'news deleted successfully',
                'status_code' => 200
            ];
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
                'status_code' => $e->getCode()
            ];
        }
    }
}
