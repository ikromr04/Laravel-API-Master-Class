<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceAuthorRequest;
use App\Http\Requests\Api\V1\UpdateAuthorRequest;
use App\Http\Resources\V1\AuthorResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketAuthorController extends ApiController
{
    /**
     * Show an author for a given ticket, optionally applying filters.
     */
    public function show(AuthorFilter $filter, string $ticketId): AuthorResource|JsonResponse
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        return new AuthorResource($ticket->author()->filter($filter)->first());
    }

    /**
     * Replace an author for a given ticket.
     */
    public function replace(ReplaceAuthorRequest $request, string $ticketId): AuthorResource|JsonResponse
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $author = $ticket->author;
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $author->update($request->mappedAttributes());

        return new AuthorResource($author);
    }

    /**
     * Update an author for a given ticket.
     */
    public function update(UpdateAuthorRequest $request, string $ticketId): AuthorResource|JsonResponse
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $author = $ticket->author;
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $author->update($request->mappedAttributes());

        return new AuthorResource($author);
    }

    /**
     * Delete an author for a given ticket.
     */
    public function destroy(string $ticketId): JsonResponse
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $author = $ticket->author;
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $author->delete();

        return $this->ok('Author successfully deleted.');
    }
}
