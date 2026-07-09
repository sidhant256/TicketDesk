<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAgentsOfNewTicket implements ShouldQueue
{
    public function handle(TicketCreated $event): void
    {
        $agents = User::where('role', 'agent')->get();

        foreach ($agents as $agent) {
            $agent->notify(new NewTicketNotification($event->ticket));
        }
    }
}