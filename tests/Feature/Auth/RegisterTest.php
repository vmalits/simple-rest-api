<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers successfully and returns a bearer token with 201 status', function () {
    $payload = [
        'name'                  => 'Vladimir Malits',
        'email'                 => 'vladimir.malits@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson('/api/v1/register', $payload);

    $response->assertCreated()
        ->assertJsonStructure([
            'token',
            'type',
        ])
        ->assertJson([
            'type' => 'Bearer',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'vladimir.malits@example.com',
        'name'  => 'Vladimir Malits',
    ]);
});

it('validates input on registration for missing fields', function () {
    $this->postJson('/api/v1/register', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('validates input on registration for invalid email and short password', function () {
    $this->postJson('/api/v1/register', [
        'name'                  => 'John Doe',
        'email'                 => 'not-an-email',
        'password'              => 'short',
        'password_confirmation' => 'short',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('fails registration when password confirmation does not match', function () {
    $this->postJson('/api/v1/register', [
        'name'                  => 'John Doe',
        'email'                 => 'john.mismatch@example.com',
        'password'              => 'password',
        'password_confirmation' => 'different-password',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('fails registration when email is already taken', function () {
    User::factory()->create([
        'email' => 'taken@example.com',
    ]);

    $this->postJson('/api/v1/register', [
        'name'                  => 'Jane Doe',
        'email'                 => 'taken@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});
