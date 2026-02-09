<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketCollection;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthorTicketsController extends ApiController
{
    /**
     * List all tickets for a given author with optional filtering.
     */
    public function index(string $authorId, TicketFilter $filter): TicketCollection
    {
        $tickets = Ticket::where('user_id', $authorId)->filter($filter)->get();

        return new TicketCollection($tickets);
    }

    /**
     * Create a new ticket for a given author.
     */
    public function store(StoreTicketRequest $request, string $authorId): TicketResource|JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $attributes = $request->mappedAttributes();
        $attributes['user_id'] = $author->id;

        $ticket = Ticket::create($attributes);

        return new TicketResource($ticket);
    }

    /**
     * Show a specific ticket for a given author, optionally applying filters.
     */
    public function show(TicketFilter $filter, string $authorId, string $ticketId): TicketResource|JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $ticket = $author->tickets()->filter($filter)->find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        return new TicketResource($ticket);
    }

    /**
     * Replace a ticket for a given author.
     */
    public function replace(ReplaceTicketRequest $request, string $authorId, string $ticketId): TicketResource|JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $ticket = $author->tickets()->find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $attributes = $request->mappedAttributes();
        $attributes['user_id'] = $author->id;

        $ticket->update($attributes);

        return new TicketResource($ticket);
    }

    /**
     * Replace a ticket for a given author.
     */
    public function update(UpdateTicketRequest $request, string $authorId, string $ticketId): TicketResource|JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $ticket = $author->tickets()->find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $attributes = $request->mappedAttributes();
        $attributes['user_id'] = $author->id;

        $ticket->update($attributes);

        return new TicketResource($ticket);
    }

    /**
     * Delete a ticket for a given author.
     */
    public function destroy(string $authorId, string $ticketId): JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $ticket = $author->tickets()->find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $ticket->delete();

        return $this->ok('Ticket successfully deleted.');
    }
}
