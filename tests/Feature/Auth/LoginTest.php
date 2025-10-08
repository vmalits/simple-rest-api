<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('logs in successfully and returns a bearer token', function () {
    $user = User::factory()->create([
        'email' => 'vladimir@example.com',
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'vladimir@example.com',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'token',
            'type',
        ])
        ->assertJson([
            'type' => 'Bearer',
        ]);
});

it('validates input on login', function () {
    $this->postJson('/api/v1/login', [])->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);

    $this->postJson('/api/v1/login', [
        'email' => 'not-an-email',
        'password' => 'short',
    ])->assertUnprocessable()
      ->assertJsonValidationErrors(['email', 'password']);
});

it('fails to login with incorrect credentials', function () {
    User::factory()->create(['email' => 'wrongpass@example.com']);

    $this->postJson('/api/v1/login', [
        'email' => 'wrongpass@example.com',
        'password' => 'incorrect-password',
    ])->assertUnprocessable()
      ->assertJsonValidationErrors(['email']);
});
