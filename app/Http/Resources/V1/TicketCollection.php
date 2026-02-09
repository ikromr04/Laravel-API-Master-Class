<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\AuthorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => TicketResource::collection($this->collection),
        ];
    }

    public function with($request): array
    {
        if (! $this->collection->first()?->relationLoaded('author')) {
            return [];
        }

        return [
            'included' => $this->collection
                ->pluck('author')
                ->unique('id')
                ->filter()
                ->map(fn($author) => new AuthorResource($author))
                ->values(),
        ];
    }
}
