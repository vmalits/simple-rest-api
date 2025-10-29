<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\V1\StoreRequest;
use App\Http\Resources\Users\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;

#[Group('Users', 'Endpoints related to user management.')]
class StoreController extends Controller
{
    #[Endpoint('Create user', 'Creates a new user and returns the created resource. Requires authentication.')]
    #[Authenticated]
    #[BodyParam('name', 'string', 'The user\'s full name.', required: true, example: 'Alice Example')]
    #[BodyParam('email', 'string', 'The user\'s email address.', required: true, example: 'alice@example.com')]
    #[BodyParam('password', 'string', 'The user\'s password.', required: true, example: 'P@ssw0rd!23')]
    #[ScribeResponse([
        'data' => [
            'id' => 1,
            'name' => 'Alice Example',
            'email' => 'alice@example.com',
        ],
    ], status: 201, description: 'User created successfully.')]
    #[ScribeResponse([
        'message' => 'Unauthenticated.'
    ], status: 401, description: 'Unauthorized.')]
    #[ScribeResponse([
        'message' => 'The given data was invalid.',
        'errors' => [
            'email' => ['The email has already been taken.'],
        ],
    ], status: 422, description: 'Validation error.')]
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return new JsonResponse(
            data: new UserResource($user),
            status: Response::HTTP_CREATED
        );
    }
}
