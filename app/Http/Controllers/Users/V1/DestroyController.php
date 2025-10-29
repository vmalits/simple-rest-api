<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Responses\MessageResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;

#[Group('Users', 'Endpoints related to user management.')]
class DestroyController extends Controller
{
    #[Endpoint('Delete user', 'Deletes an existing user by ID. Requires authentication.')]
    #[Authenticated]
    #[UrlParam('id', 'integer', 'The ID of the user to delete.', example: 1)]
    #[ScribeResponse([
        'message' => 'User deleted successfully'
    ], status: 204, description: 'User deleted successfully.')]
    #[ScribeResponse([
        'message' => 'Unauthenticated.'
    ], status: 401, description: 'Unauthorized.')]
    #[ScribeResponse([
        'message' => 'No query results for model [App\\Models\\User] 999.'
    ], status: 404, description: 'User not found.')]
    public function __invoke(User $user): MessageResponse
    {
        $user->delete();

        return new MessageResponse(
            data: 'User deleted successfully',
            status: Response::HTTP_NO_CONTENT
        );
    }
}
