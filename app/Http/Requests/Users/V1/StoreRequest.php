<?php

declare(strict_types=1);

namespace App\Http\Requests\Users\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::default()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
