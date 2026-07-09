<?php

namespace App\Providers;
use App\Events\TicketCreated;
use Illuminate\Support\Facades\Event;
use App\Listeners\NotifyAgentsOfNewTicket;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(TicketCreated::class, NotifyAgentsOfNewTicket::class);
    }
}