<?php

namespace App\Http\Requests\v1\cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCartRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        if (Auth('sanctum')->user()) {
            $this->merge([
                'user_id' => Auth('sanctum')->user()->id,
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer'],
            'cart_token' => ['nullable', 'string', Rule::exists('carts', 'token')],
            'products_ids' => ['required', 'array'],
            'products_ids.*' => [
                'integer',
                Rule::exists('products', 'id')
                    ->where('is_active', true)
            ]
        ];
    }
}
