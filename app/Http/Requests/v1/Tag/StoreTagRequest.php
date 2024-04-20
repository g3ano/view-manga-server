<?php

namespace App\Http\Requests\v1\Tag;

use App\Http\Requests\v1\BaseRequest;
use Illuminate\Validation\Rule;

class StoreTagRequest extends BaseRequest
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
            'name' => ['required', 'string', 'max:50', Rule::unique('tags', 'name')],
            'type' => ['required', 'string', 'max:50', Rule::in([
                'genre',
                'theme',
                'demographic'
            ])],
        ];
    }
}
