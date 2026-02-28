<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'nullable|image|max:2048',
            'url' => 'required|string|url|max:500',
            'is_published' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت',
            'url.required' => 'رابط الكتاب مطلوب',
            'url.url' => 'يجب أن يكون الرابط بصيغة صحيحة',
        ];
    }
}
