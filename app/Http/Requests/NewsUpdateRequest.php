<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\News;
use App\Lib\Utils;
use Closure;

class NewsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                function (string $attribute, mixed $value, Closure $fail) {
                    $user = Auth::user();
                    $current_slug = $this->route('slug');
                    $new_slug = Utils::slug($value);

                    $current_news = News::where([
                        ['user_id', '=', $user->id],
                        ['slug', '=', $current_slug]
                    ])->first();

                    $new_news = News::where([
                        ['user_id', '=', $user->id],
                        ['slug', '=', $new_slug]
                    ])->first();

                    if ($new_news && ($current_news->id != $new_news->id)) {
                        $fail('news already exist');
                    }
                }
            ],
            'body' => ['required']
        ];
    }
}
