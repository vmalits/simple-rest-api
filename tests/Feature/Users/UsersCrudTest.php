<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function apiUrl(string $path): string
{
    return '/api/v1' . $path;
}

it('requires authentication for users endpoints', function () {
    $this->getJson(apiUrl('/users'))
        ->assertUnauthorized();

    $user = User::factory()->create();
    $this->getJson(apiUrl('/users/' . $user->id))
        ->assertUnauthorized();

    $this->postJson(apiUrl('/users'), [])
        ->assertUnauthorized();

    $this->putJson(apiUrl('/users/' . $user->id), [])
        ->assertUnauthorized();

    $this->deleteJson(apiUrl('/users/' . $user->id))
        ->assertUnauthorized();
});

it('lists users with pagination data', function () {
    Sanctum::actingAs(User::factory()->create());

    User::factory()->count(25)->create();

    $response = $this->getJson(apiUrl('/users?per_page=10'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email' => ['address', 'verified'], 'created_at' => ['human', 'string']],
            ],
            'links',
            'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
        ]);

    expect(count($response->json('data')))->toBe(10);
});

it('shows a single user', function () {
    Sanctum::actingAs(User::factory()->create());

    $user = User::factory()->create();

    $this->getJson(apiUrl('/users/' . $user->id))
        ->assertOk()
        ->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => [
                'address' => $user->email,
                'verified' => false,
            ],
        ])
        ->assertJsonStructure([
            'id',
            'name',
            'email' => ['address', 'verified'],
            'created_at' => ['human', 'string']
        ]);
});

it('creates a user', function () {
    Sanctum::actingAs(User::factory()->create());

    $payload = [
        'name' => 'John Doe',
        'email' => 'johndoe@gmail.com',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
    ];

    $response = $this->postJson(apiUrl('/users'), $payload);

    $response->assertCreated();

    $response->assertJsonStructure([
        'id',
        'name',
        'email' => ['address', 'verified'],
        'created_at' => ['human', 'string']
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'johndoe@gmail.com',
        'name' => 'John Doe',
    ]);
});

it('validates input on create', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson(apiUrl('/users'), [])->assertUnprocessable();
});

it('updates a user', function () {
    Sanctum::actingAs(User::factory()->create());

    $user = User::factory()->create();

    $payload = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ];

    $this->putJson(apiUrl('/users/' . $user->id), $payload)
        ->assertOk()
        ->assertJsonFragment([
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('deletes a user', function () {
    Sanctum::actingAs(User::factory()->create());

    $user = User::factory()->create();

    $this->deleteJson(apiUrl('/users/' . $user->id))
        ->assertNoContent();

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});
