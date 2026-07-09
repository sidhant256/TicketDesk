<?php

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires authentication to list tickets', function () {
    $this->getJson('/api/tickets')->assertUnauthorized();
});

it('lets a customer create a ticket', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $category = Category::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/tickets', [
        'title' => 'Cannot log in',
        'description' => 'Getting a 500 error on login',
        'category_id' => $category->id,
        'priority' => 'high',
    ]);

    $response->assertCreated();
    $response->assertJsonPath('data.title', 'Cannot log in');
    expect(Ticket::count())->toBe(1);
});

it('only shows a customer their own tickets', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $otherUser = User::factory()->create(['role' => 'customer']);

    Ticket::factory()->create(['user_id' => $user->id]);
    Ticket::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->getJson('/api/tickets');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
});

it('lets an agent see all tickets', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    Ticket::factory()->count(3)->create();

    $response = $this->actingAs($agent)->getJson('/api/tickets');

    $response->assertJsonCount(3, 'data');
});

it('prevents a customer from updating someone elses ticket', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $ticket = Ticket::factory()->create();

    $this->actingAs($user)->patchJson("/api/tickets/{$ticket->id}", ['status' => 'closed'])
        ->assertForbidden();
});