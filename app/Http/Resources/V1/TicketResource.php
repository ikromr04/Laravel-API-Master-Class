<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'tickets',
            'id' => (string) $this->id,

            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
                'createdAt' => $this->created_at?->toISOString(),
                'updatedAt' => $this->updated_at?->toISOString()
            ],

            'relationships' => [
                'author' => [
                    'links' => [
                        'self' => route('tickets.show', ['ticket' => $this->id]),
                        'related' => route('tickets.author.index', ['ticket' => $this->id])
                    ],
                    'data' => [
                        'type' => 'users',
                        'id' => (string) $this->user_id
                    ]
                ]
            ],
        ];
    }

    public function with(Request $request): array
    {
        if (! $this->relationLoaded('author') || ! $this->author) {
            return [];
        }

        return [
            'included' => [
                new AuthorResource($this->author),
            ],
        ];
    }
}
