<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\V1\RegistrationRequest;
use App\Http\Support\Result;
use App\Responses\TokenResponse;
use App\Services\IdentityService;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

#[Group('Auth')]
final class RegisterController extends Controller
{
    public function __construct(private readonly IdentityService $identityService)
    {
    }

    /**
     * @throws Throwable
     */
    #[BodyParam('name', 'string', required: true, example: 'John Doe')]
    #[BodyParam('email', 'string', required: true, example: 'johndoe@gmail.com')]
    #[BodyParam('password', 'string', required: true, example: 'strongpassword123')]
    #[BodyParam('password_confirmation', 'string', required: true, example: 'strongpassword123')]
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
