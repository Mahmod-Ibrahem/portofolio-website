<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'link' => 'nullable|string|url',
            'cover_image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المقالة مطلوب',
            'content.required' => 'محتوى المقالة مطلوب',
            'link.url' => 'يجب أن يكون الرابط بصيغة صحيحة',
            'cover_image.image' => 'يجب أن تكون صورة الغلاف من نوع صورة',
            'cover_image.max' => 'حجم صورة الغلاف لا يجب أن يتجاوز 2 ميجابايت',
        ];
    }
}
