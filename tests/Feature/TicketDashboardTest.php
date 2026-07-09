<?php

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('shows tickets to a logged in agent', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    Ticket::factory()->create(['title' => 'Broken login']);

    Livewire::actingAs($agent)
        ->test('pages::ticket-dashboard')
        ->assertSee('Broken login');
});

it('filters tickets by status', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    Ticket::factory()->create(['title' => 'Open ticket', 'status' => 'open']);
    Ticket::factory()->create(['title' => 'Closed ticket', 'status' => 'closed']);

    Livewire::actingAs($agent)
        ->test('pages::ticket-dashboard')
        ->set('statusFilter', 'closed')
        ->assertSee('Closed ticket')
        ->assertDontSee('Open ticket');
});

it('updates a ticket status inline', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $ticket = Ticket::factory()->create(['status' => 'open']);

    Livewire::actingAs($agent)
        ->test('pages::ticket-dashboard')
        ->call('updateStatus', $ticket->id, 'closed');

    expect($ticket->fresh()->status)->toBe('closed');
});