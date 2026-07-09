<?php

use App\Events\TicketCreated;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('fires the TicketCreated event when a ticket is made', function () {
    Event::fake();

    $user = User::factory()->create(['role' => 'customer']);
    $category = Category::factory()->create();

    $this->actingAs($user)->postJson('/api/tickets', [
        'title' => 'Testing events',
        'description' => 'Does this fire?',
        'category_id' => $category->id,
        'priority' => 'low',
    ]);

    Event::assertDispatched(TicketCreated::class);
});