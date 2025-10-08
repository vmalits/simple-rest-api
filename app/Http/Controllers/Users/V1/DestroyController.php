<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Responses\MessageResponse;
use Symfony\Component\HttpFoundation\Response;

class DestroyController extends Controller
{
    public function __invoke(User $user)
    {
        $user->delete();

        return new MessageResponse(
            data: 'User deleted successfully',
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
