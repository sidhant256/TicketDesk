<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('logs in a user with correct credentials and returns a token', function () {
    $user = User::factory()->create([
        'email' => 'sid@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'sid@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['user', 'token']);
});

it('rejects login with incorrect password', function () {
    User::factory()->create([
        'email' => 'sid@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'sid@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
});

it('logs out a user and revokes their token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/logout');

    $response->assertOk();
    expect($user->tokens()->count())->toBe(0);
});