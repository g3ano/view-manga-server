<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'attributes' => [
                'id' => $this->id,
                'username' => $this->username,
                'slug' => $this->slug,
                'email' => $this->email,
                'isLeader' => $this->whenNotNull(
                    $this->whenPivotLoaded(
                        'team_user',
                        $this->pivot?->is_leader === 1
                            ? true : null
                    )
                ),
                'isBelowCountLimit' => $this->whenNotNull(
                    $this->isBelowLimit ?: null
                ),
                'joinedAt' => $this->whenPivotLoaded(
                    'team_user',
                    strtotime($this->pivot?->created_at) ?: null
                ),
                'createdAt' => strtotime($this->created_at),
            ],
            'relationships' => [
                'roles' => $this->whenLoaded('roles') instanceof MissingValue
                    ? $this->whenLoaded('roles')
                    : $this->whenLoaded('roles')
                    ->pluck('name')
                    ->sort()->flatten(1)->add('member'),
                'teams' => TeamResource::collection($this->whenLoaded('teams')),
            ],
        ];
    }
}
