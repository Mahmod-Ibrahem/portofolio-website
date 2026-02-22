<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image'      => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
            'title'      => 'nullable|string|max:255',
            'alt_text'   => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image.file'  => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'الصيغ المسموحة: jpg, jpeg, png, webp',
            'image.max'   => 'حجم الصورة لا يجب أن يتجاوز 4 ميجابايت',
        ];
    }
}
