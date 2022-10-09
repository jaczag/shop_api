<?php

namespace App\Http\Requests\v1\product;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:50'],
            'price' => ['required', 'numeric', 'between:0.01,9999999.99'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'is_active' =>['nullable', 'boolean', Rule::in([0,1])],
            'description' => ['nullable', 'string','max:400'],
        ];
    }
}
