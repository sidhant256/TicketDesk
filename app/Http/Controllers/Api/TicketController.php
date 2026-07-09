<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Events\TicketCreated;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = $request->user()->role === 'agent'
            ? Ticket::with(['category', 'user', 'comments'])->latest()->get()
            : Ticket::with(['category', 'user', 'comments'])->where('user_id', $request->user()->id)->latest()->get();

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = $request->user()->tickets()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'priority' => $request->input('priority'),
        ]);

        $ticket->refresh();

        TicketCreated:: dispatch($ticket);

        return new TicketResource($ticket->load(['category', 'user', 'comments']));
    }

    public function show(Request $request, Ticket $ticket)
    {
        abort_if(
            $request->user()->role !== 'agent' && $ticket->user_id !== $request->user()->id,
            403,
            'You are not authorized to view this ticket.'
        );

        return new TicketResource($ticket->load(['category', 'user', 'comments']));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        abort_if(
            $request->user()->role !== 'agent' && $ticket->user_id !== $request->user()->id,
            403,
            'You are not authorized to update this ticket.'
        );

        $ticket->update($request->validated());

        return new TicketResource($ticket->load(['category', 'user', 'comments']));
    }

    public function destroy(string $id)
    {
        //
    }
}