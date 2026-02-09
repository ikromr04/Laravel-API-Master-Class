<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into a JSON:API compliant array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'users',
            'id' => (string) $this->id,

            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'emailVerifiedAt' => $this->email_verified_at?->toISOString(),
                'createdAt' => $this->created_at?->toISOString(),
                'updatedAt' => $this->updated_at?->toISOString()
            ],

            'relationships' => [
                'tickets' => [
                    'links' => [
                        'related' => route('authors.tickets.index', ['author' => $this->id])
                    ],
                    'data' => $this->whenLoaded('tickets', fn() => $this->tickets->map(fn($ticket) => [
                        'type' => 'tickets',
                        'id' => (string) $ticket->id,
                    ]))
                ]
            ],

            'links' => [
                'self' => route('authors.show', ['author' => $this->id]),
            ],
        ];
    }

    /**
     * Add included relationships when loaded.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        if (! $this->relationLoaded('tickets')) {
            return [];
        }

        return [
            'included' => TicketResource::collection($this->tickets),
        ];
    }
}
