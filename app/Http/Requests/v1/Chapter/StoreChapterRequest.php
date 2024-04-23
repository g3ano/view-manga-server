<?php

namespace App\Http\Requests\v1\Chapter;

use App\Http\Requests\v1\BaseRequest;
use Illuminate\Validation\Rule;

class StoreChapterRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manga_id' => ['bail', 'required', Rule::exists('mangas', 'id')],
            'team_id' => ['bail', 'required', Rule::exists('teams', 'id')],
            'number' => [
                'bail',
                'required',
                'numeric',
                'min:0',
            ],
            'title' => [
                'bail',
                'nullable',
                'string',
                'max:255',
            ],
            'pages' => ['bail', 'required', 'file', 'mimes:zip', 'max:51200'],
        ];
    }
}
