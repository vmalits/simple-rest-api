<?php

declare(strict_types=1);

namespace App\Responses;

use App\Http\Support\Headers;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use SensitiveParameter;
use Symfony\Component\HttpFoundation\Response;

readonly class TokenResponse implements Responsable
{
    public function __construct(
        #[SensitiveParameter]
        private string $token,
        private int $status = Response::HTTP_OK,
    ) {
    }

    public function toResponse($request): Response
    {
        return new JsonResponse(
            data: [
                'token' => $this->token,
                'type'  => 'Bearer',
            ],
            status: $this->status,
            headers: Headers::default(),
        );
    }
}
