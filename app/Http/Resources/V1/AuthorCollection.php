<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => AuthorResource::collection($this->collection),
        ];
    }

    public function with($request): array
    {
        if (! $this->collection->first()?->relationLoaded('tickets')) {
            return [];
        }

        return [
            'included' => $this->collection
                ->pluck('tickets')
                ->flatten()
                ->unique('id')
                ->filter()
                ->map(fn($ticket) => new TicketResource($ticket))
                ->values(),
        ];
    }
}
