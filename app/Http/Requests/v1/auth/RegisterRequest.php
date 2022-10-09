<?php

namespace App\Http\Requests\v1\auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @property mixed $password
 */
class RegisterRequest extends FormRequest
{
    /**
     * authorize request
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * validation rules
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')],
            'password' => ['required', 'string', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }
}
