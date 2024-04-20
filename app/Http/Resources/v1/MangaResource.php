<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MangaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'manga',
            'attributes' => [
                'id' => $this->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'titleEn' => $this->title_en ?? '',
                'titleAr' => $this->title_ar ?? '',
                'description' => $this->description ?? '',
                'mangaStatus' => $this->manga_status,
                'translationStatus' => $this->translation_status,
                'author' => $this->author ?: '',
                'cover' => $this->cover ?: '',
                'createdAt' => strtotime($this->created_at),
                'isApproved' => $this->when(
                    $this->is_approved === 0,
                    false
                ),
            ],
            'relationships' => [
                'team' => new TeamResource($this->whenLoaded('team')),
                'tags' => TagResource::collection($this->whenLoaded('tags')),
            ]
        ];
    }
}
