<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\V1\RegistrationRequest;
use App\Http\Support\Result;
use App\Responses\TokenResponse;
use App\Services\IdentityService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RegisterController extends Controller
{
    public function __construct(private readonly IdentityService $identityService)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(RegistrationRequest $request): TokenResponse
    {
        $result = $this->identityService->register($request->payload());

        if ($result->isError()) {
            throw $result->error;
        }

        return new TokenResponse(
            token: $result->value,
            status: Response::HTTP_CREATED
        );
    }
}
