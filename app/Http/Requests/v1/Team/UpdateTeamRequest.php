<?php

namespace App\Http\Requests\v1\Team;

use App\Http\Requests\v1\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateTeamRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'max:100',
                Rule::unique('teams', 'name')->ignore($this->id)
            ],
            'description' => ['bail', 'required', 'string', 'max:255'],
            'website' => ['bail', 'nullable', 'string', 'max:255'],
            'twitter' => ['bail', 'nullable', 'string', 'max:255'],
            'facebook' => ['bail', 'nullable', 'string', 'max:255'],
            'discord' => ['bail', 'nullable', 'string', 'max:255'],
            'members' => ['bail', 'nullable', 'array'],
            'members.*' => [
                'bail',
                'required',
                'integer',
                Rule::exists('users', 'id')->whereNot('id', $this->user()->id),
            ],
        ];
    }
}
