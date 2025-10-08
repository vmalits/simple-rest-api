<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\V1\StoreRequest;
use App\Http\Resources\Users\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return new JsonResponse(
            data: new UserResource($user),
            status: Response::HTTP_CREATED,
        );
    }
}
