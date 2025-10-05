<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Dtos\Auth\V1\LoginData;
use App\Http\Dtos\Auth\V1\RegistrationData;
use App\Http\Support\Result;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use RuntimeException;

readonly class IdentityService implements IdentityServiceContract
{
    public function __construct(private DatabaseManager $databaseManager)
    {
    }

    public function login(LoginData $loginData): Result
    {
        $user = User::query()->where('email', $loginData->email)->first();

        if (!$user || !Hash::check($loginData->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return Result::ok(
            value: $user->createToken('token')->plainTextToken
        );
    }

    /**
     * @throws \Throwable
     */
    public function register(RegistrationData $registrationData): Result
    {
        $user = $this->databaseManager->transaction(
            callback: fn() => User::query()->create([
                'name' => $registrationData->name,
                'email' => $registrationData->email,
                'password' => $registrationData->password,
            ]),
            attempts: 3
        );

        if (!$user) {
            return Result::error(
                error: new RuntimeException('Failed to create user.')
            );
        }

        return Result::ok(
            value: $user->createToken('token')->plainTextToken
        );
    }
}