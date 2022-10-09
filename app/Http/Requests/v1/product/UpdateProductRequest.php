<?php

namespace App\Http\Requests\v1\product;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'min:3', 'max:50'],
            'price' => ['nullable', 'numeric', 'between:0.01,9999999.99'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'is_active' =>['nullable', 'boolean', Rule::in([0,1])],
            'description' => ['nullable', 'string','max:400'],
        ];
    }
}
