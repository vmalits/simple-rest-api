<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth\V1;

use App\Dtos\Auth\V1\RegistrationData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function payload(): RegistrationData
    {
        return new RegistrationData(
            name: $this->string('name')->toString(),
            email: $this->string('email')->toString(),
            password: $this->string('password')->toString(),
        );
    }
}
