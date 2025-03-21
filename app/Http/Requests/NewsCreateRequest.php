<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\News;
use App\Lib\Utils;
use Closure;

class NewsCreateRequest extends FormRequest
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

                    $news_already_exist = News::where([
                        ['user_id', '=', $user->id],
                        ['slug', '=', Utils::slug($value)]
                    ])->first();
                    if ($news_already_exist) {
                        $fail('news already exist');
                    }
                }
            ],
            'body' => ['required'],
            'thumbnail' => ['required', 'file', 'mimes:jpg,jpeg,png'],
            'pictures' => ['required', 'array'],
            'pictures.*' => ['required', 'file', 'mimes:jpg,jpeg,png']
        ];
    }
}
