<?php

declare(strict_types=1);

namespace App\Dtos\Auth\V1;

use SensitiveParameter;
use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        public readonly string $email,
        #[SensitiveParameter]
        public readonly string $password,
    ) {
    }
}