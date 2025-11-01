<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\V1\UserResource;
use App\Models\User;
use App\Responses\ModelResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response;

#[Group('Users', 'Endpoints related to user management.')]
final class ShowController extends Controller
{
    #[Endpoint('Show user', 'Retrieve a single user by ID. Returns detailed user information.')]
    #[Authenticated]
    #[ScribeResponse([
        'data' => [
            'id'    => 1,
            'name'  => 'Alice Example',
            'email' => 'alice@example.com',
        ],
    ], status: 200, description: 'User retrieved successfully.')]
    #[ScribeResponse([
        'message' => 'Unauthenticated.',
    ], status: 401, description: 'Unauthorized.')]
    #[ScribeResponse([
        'message' => 'No query results for model [App\\Models\\User] 999.',
    ], status: 404, description: 'User not found.')]
    public function __invoke(User $user): ModelResponse
    {
        return new ModelResponse(
            data: new UserResource(resource: $user),
            status: Response::HTTP_OK,
        );
    }
}
