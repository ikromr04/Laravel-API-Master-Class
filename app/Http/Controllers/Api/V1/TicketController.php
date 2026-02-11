<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Models\Ticket;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketCollection;
use App\Http\Resources\V1\TicketResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class TicketController extends ApiController
{
    /**
     * List tickets with optional filtering.
     */
    public function index(TicketFilter $filter): TicketCollection
    {
        $tickets = Ticket::filter($filter)->paginate();

        return new TicketCollection($tickets);
    }

    /**
     * Create a new ticket.
     */
    public function store(StoreTicketRequest $request): TicketResource
    {
        $ticket = Ticket::create($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    /**
     * Show a single ticket.
     */
    public function show(TicketFilter $filter, string $ticketId): TicketResource|JsonResponse
    {
        $ticket = Ticket::filter($filter)->find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        return new TicketResource($ticket);;
    }

    /**
     * Replace an existing ticket.
     */
    public function replace(ReplaceTicketRequest $request, string $ticketId)
    {
        return $request->mappedAttributes();
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    /**
     * Update an existing ticket.
     */
    public function update(UpdateTicketRequest $request, string $ticketId): TicketResource|JsonResponse
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    /**
     * Delete a ticket.
     */
    public function destroy(string $ticketId): JsonResponse
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) {
            return $this->notFound('Ticket cannot be found.');
        }

        $ticket->delete();

        return $this->ok('Ticket successfully deleted.');
    }
}
