<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'sometimes|required|string|url|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الفيديو مطلوب',
            'video_url.required' => 'رابط الفيديو مطلوب',
            'video_url.url' => 'رابط الفيديو غير صالح',
            'thumbnail.image' => 'يجب أن تكون الصورة المصغرة من نوع صورة',
            'thumbnail.max' => 'حجم الصورة المصغرة لا يجب أن يتجاوز 2 ميجابايت',
        ];
    }
}
