<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\Auth\V1\LoginData;
use App\Dtos\Auth\V1\RegistrationData;
use App\Http\Support\Result;

interface IdentityServiceContract
{
    public function login(LoginData $loginData): Result;

    public function register(RegistrationData $registrationData): Result;
}