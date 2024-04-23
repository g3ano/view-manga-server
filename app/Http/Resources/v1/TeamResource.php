<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'team',
            'attributes' => [
                'id' => $this->id,
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'email' => $this->email,
                'social' => $this->when(
                    !is_null($this->website) ||
                        !is_null($this->twitter) ||
                        !is_null($this->facebook) ||
                        !is_null($this->discord),
                    [
                        'website' => $this->whenNotNull($this->website),
                        'twitter' => $this->whenNotNull($this->twitter),
                        'facebook' => $this->whenNotNull($this->facebook),
                        'discord' => $this->whenNotNull($this->discord),
                    ]
                ),
                'isLeader' => $this->when(
                    ($this->hasPivotLoaded('team_user') &&
                        $this->pivot?->is_leader) ||
                         $this->isLeader ?: false,
                    true,
                ),
                'isMember' => $this->whenNotNull(
                    $this->isMember ?: null
                ),
                'isPending' => $this->whenNotNull(
                    $this->isPending ?: null
                ),
                'isRefused' => $this->whenNotNull(
                    $this->isRefused ?: null
                ),
                'isNameAscii' => $this->whenNotNull(
                    Str::isAscii($this->name) ?: null
                ),
                'joinedAt' => $this->whenPivotLoaded(
                    'team_user',
                    strtotime($this->pivot?->created_at)
                ),
                'publishedAt' => $this->whenPivotLoaded(
                    'chapter_team',
                    strtotime($this->pivot?->created_at)
                ),
                'createdAt' => strtotime($this->created_at),
            ],
            'relationships' => [
                'members' => new UserCollection($this->whenLoaded('members')),
                'mangas' => new MangaCollection($this->whenLoaded('mangas')),
            ]
        ];
    }
}
