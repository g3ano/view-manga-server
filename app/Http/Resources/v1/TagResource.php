<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'tag',
            'attributes' => [
                'id' => $this->id,
                'name' => $this->name,
                'type' => $this->type
            ],
            'relationships' => []
        ];
    }
}
