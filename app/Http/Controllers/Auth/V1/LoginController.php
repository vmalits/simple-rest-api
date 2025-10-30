<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\V1\LoginRequest;
use App\Responses\TokenResponse;
use App\Services\IdentityService;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group('Auth')]
final class LoginController extends Controller
{
    public function __construct(private readonly IdentityService $identityService)
    {
    }

    #[BodyParam('email', 'string', required: true, example: 'johndoe@gmail.com')]
    #[BodyParam('password', 'string', required: true, example: 'strongpassword123')]
    public function __invoke(LoginRequest $request): Responsable
    {
        $result = $this->identityService->login($request->payload());

        if ($result->isError()) {
            throw $result->error;
        }

        return new TokenResponse(
            token: $result->value
        );
    }
}
