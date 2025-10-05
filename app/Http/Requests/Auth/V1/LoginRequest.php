<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth\V1;

use App\Http\Dtos\Auth\V1\LoginData;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function payload(): LoginData
    {
        return new LoginData(
            email: $this->string('email')->toString(),
            password: $this->string('password')->toString(),
        );
    }
}
