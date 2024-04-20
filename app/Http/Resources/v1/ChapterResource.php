<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'chapter',
            'attributes' => [
                'id' => $this->id,
                'number' => str_contains($this->number, '.')
                    ? (float) $this->number
                    : (int) $this->number,
                'title' => $this->whenNotNull(
                    $this->title ?: null
                ),
                'createdAt' => strtotime($this->created_at),
            ],
            'relationships' => [
                'manga' => new MangaResource($this->whenLoaded('manga')),
                'teams' => new TeamCollection($this->whenLoaded('teams')),
                'pages' => new PageResource($this->whenLoaded('pages')),
            ]
        ];
    }
}
