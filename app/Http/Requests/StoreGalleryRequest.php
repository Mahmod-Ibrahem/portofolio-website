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
            // single upload
            'image'    => 'required_without:images|file|mimes:jpg,jpeg,png,webp|max:4096',
            // multi upload
            'images'   => 'required_without:image|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:4096',
            'title'    => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required_without'  => 'يجب رفع صورة واحدة على الأقل',
            'image.file'              => 'يجب أن يكون الملف صورة',
            'image.mimes'             => 'الصيغ المسموحة: jpg, jpeg, png, webp',
            'image.max'               => 'حجم الصورة لا يجب أن يتجاوز 4 ميجابايت',
            'images.required_without' => 'يجب رفع صورة واحدة على الأقل',
            'images.*.file'           => 'يجب أن يكون كل ملف صورة',
            'images.*.mimes'          => 'الصيغ المسموحة: jpg, jpeg, png, webp',
            'images.*.max'            => 'حجم كل صورة لا يجب أن يتجاوز 4 ميجابايت',
        ];
    }
}
