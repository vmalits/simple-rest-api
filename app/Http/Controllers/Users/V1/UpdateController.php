<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\V1\UpdateRequest;
use App\Models\User;
use App\Responses\ModelResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response;

#[Group('Users', 'Endpoints related to user management.')]
final class UpdateController extends Controller
{
    #[Endpoint('Update user', 'Updates an existing user and returns the updated resource.')]
    #[Authenticated]
    #[BodyParam('name', 'string', 'The user\'s full name.', required: false, example: 'Alice Updated')]
    #[BodyParam('email', 'string', 'The user\'s email address.', required: false, example: 'alice.updated@example.com')]
    #[BodyParam('password', 'string', 'The user\'s new password.', required: false, example: 'N3wP@ssw0rd!')]
    #[ScribeResponse([
        'data' => [
            'id'    => 1,
            'name'  => 'Alice Updated',
            'email' => 'alice.updated@example.com',
        ],
    ], status: 200, description: 'User updated successfully.')]
    #[ScribeResponse([
        'message' => 'Unauthenticated.',
    ], status: 401, description: 'Unauthorized.')]
    #[ScribeResponse([
        'message' => 'The given data was invalid.',
        'errors'  => [
            'email' => ['The email has already been taken.'],
        ],
    ], status: 422, description: 'Validation error.')]
    public function __invoke(User $user, UpdateRequest $request): ModelResponse
    {
        $user->update($request->validated());

        return new ModelResponse(
            data: $user,
            status: Response::HTTP_OK,
        );
    }
}
