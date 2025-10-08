<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\V1\UpdateRequest;
use App\Models\User;
use App\Responses\ModelResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateController extends Controller
{
    public function __invoke(User $user, UpdateRequest $request): ModelResponse
    {
        $user->update($request->validated());

        return new ModelResponse(
            data: $user,
            status: Response::HTTP_OK
        );
    }
}
