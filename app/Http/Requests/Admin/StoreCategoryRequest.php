<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'parent_id'   => 'nullable|exists:categories,id',
            'color'       => 'nullable|string|max:7',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'order'       => 'integer|min:0',
            'translations'       => 'required|array',
            'translations.dv.name' => 'required|string|max:255',
            'translations.dv.slug' => 'nullable|string|max:255',
            'translations.en.name' => 'nullable|string|max:255',
        ];
    }
}
