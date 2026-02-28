<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'required|image|max:2048',
            'url' => 'required|string|url|max:500',
            'is_published' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'صورة الكتاب مطلوبة',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت',
            'url.required' => 'رابط الكتاب مطلوب',
            'url.url' => 'يجب أن يكون الرابط بصيغة صحيحة',
        ];
    }
}
