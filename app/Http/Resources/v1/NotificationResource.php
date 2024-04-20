<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'notifiableId' => $this->notifiable_id,
            'data' => $this->data,
            'readAt' => $this->whenNotNull($this->read_at),
            'createdAt' => strtotime($this->created_at),
        ];
    }
}
