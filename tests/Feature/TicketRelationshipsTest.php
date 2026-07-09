<?php

use App\Models\Category;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('associates a ticket with its user and category', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->user)->toBeInstanceOf(User::class);
    expect($ticket->category)->toBeInstanceOf(Category::class);
});

it('associates a comment with its ticket and author', function () {
    $comment = Comment::factory()->create();

    expect($comment->ticket)->toBeInstanceOf(Ticket::class);
    expect($comment->user)->toBeInstanceOf(User::class);
});

it('lists comments belonging to a ticket', function () {
    $ticket = Ticket::factory()->create();
    Comment::factory()->count(3)->create(['ticket_id' => $ticket->id]);

    expect($ticket->comments)->toHaveCount(3);
});