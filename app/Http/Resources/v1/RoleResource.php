<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'role',
            'attributes' => [
                'name' => $this->name,
                // 'createdAt' => $this->whenPivotLoaded(
                //     'role_user',
                //     strtotime($this->pivot?->created_at) ?: null
                // ),
            ]
        ];
    }
}
