<?php

declare(strict_types=1);

namespace App\Http\Dtos\Auth\V1;

use Spatie\LaravelData\Data;

class RegistrationData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}