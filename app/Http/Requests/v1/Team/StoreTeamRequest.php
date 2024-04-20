<?php

namespace App\Http\Requests\v1\Team;

use App\Http\Requests\v1\BaseRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreTeamRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes()
    {
        return [
            'members.*' => 'member',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => [
                'bail', 'required', 'string', 'max:75', Rule::unique('teams', 'name')
            ],
            'description' => ['bail', 'required', 'string', 'min:20', 'max:255'],
            'website' => ['bail', 'nullable', 'string', 'max:75'],
            'twitter' => ['bail', 'nullable', 'string', 'max:75'],
            'facebook' => ['bail', 'nullable', 'string', 'max:75'],
            'discord' => ['bail', 'nullable', 'string', 'max:75'],
        ];
    }
}
