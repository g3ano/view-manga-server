<?php

namespace App\Http\Requests\v1\Manga;

use App\Http\Requests\v1\BaseRequest;
use Illuminate\Validation\Rule;

class StoreMangaRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes()
    {
        return [
            'team_id' => 'team',
            'tags.*' => 'tag',
            'title_en' => 'title',
            'title_ar' => 'title',
            'manga_status' => 'manga status'
        ];
    }

    public function rules(): array
    {
        return [
            'cover' => ['bail', 'required', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'team_id' => ['bail', 'required', Rule::exists('teams', 'id')],
            'author' => ['bail', 'required', 'max:255', 'string'],
            'tags' => ['bail', 'required'],
            'tags.*' => ['bail', 'required', Rule::exists('tags', 'id')],
            'title' => ['bail', 'required', 'ascii', 'max:255', Rule::unique('mangas', 'title')],
            'title_en' => [
                'bail', 'nullable', 'max:255', 'string', Rule::unique('mangas', 'title_en')
            ],
            'title_ar' => [
                'bail', 'nullable', 'max:255', 'string', Rule::unique('mangas', 'title_ar')
            ],
            'description' => ['bail', 'required', 'min:20', 'max:755'],
            'manga_status' => [
                'bail', 'required', Rule::in([
                    'completed',
                    'hiatus',
                    'ongoing',
                ])
            ],
        ];
    }
}
