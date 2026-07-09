<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the login page to guests', function () {
    $this->get('/login')->assertOk();
});

it('logs an agent in and redirects to dashboard', function () {
    $agent = User::factory()->create([
        'email' => 'agent@example.com',
        'password' => bcrypt('password123'),
        'role' => 'agent',
    ]);

    $response = $this->post('/login', [
        'email' => 'agent@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($agent);
});

it('redirects guests away from the dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});