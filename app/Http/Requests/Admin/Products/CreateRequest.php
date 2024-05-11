<?php

namespace App\Http\Requests\Admin\Products;

use App\Enums\Permissions\Product as Permission;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can(Permission::PUBLISH->value);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:1', 'max: 255', 'unique:' . Product::class . ',title'],
            'SKU' => ['required', 'string', 'min:1', 'max: 35', 'unique:' . Product::class . ',SKU'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
            'new_price' => ['nullable', 'numeric', 'min:1'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'categories.*' => ['required', 'numeric', 'exists:' . Category::class . ',id'],
            'thumbnail' => ['required', 'image:png,jpeg, jpg'],
            'images.*' => ['image:png,jpeg, jpg']
        ];
    }
}
