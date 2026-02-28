<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'    => 'nullable|string|max:255',
            'image'    => 'required|file|mimes:jpg,jpeg,png,webp|max:4096',
            'alt_text' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الصورة مطلوب',
            'title.max'      => 'عنوان الصورة لا يجب أن يتجاوز 255 حرف',
            'image.required'  => 'يجب رفع صورة',
            'image.file'      => 'يجب أن يكون الملف صورة',
            'image.mimes'     => 'الصيغ المسموحة: jpg, jpeg, png, webp',
            'image.max'       => 'حجم الصورة لا يجب أن يتجاوز 4 ميجابايت',
        ];
    }
}
