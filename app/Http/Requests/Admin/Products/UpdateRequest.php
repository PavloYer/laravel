<?php

namespace App\Http\Requests\Admin\Products;

use App\Enums\Permissions\Product as Permission;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can(Permission::EDIT->value);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('product')->id;

        return [
            'title' => ['required', 'string', 'min:1', 'max: 255', Rule::unique(Product::class, 'title')->ignore($id)],
            'SKU' => ['required', 'string', 'min:1', 'max: 35', Rule::unique(Product::class, 'SKU')->ignore($id)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
            'new_price' => ['nullable', 'numeric', 'min:1'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'categories.*' => ['required', 'numeric', 'exists:' . Category::class . ',id']
        ];
    }
}
