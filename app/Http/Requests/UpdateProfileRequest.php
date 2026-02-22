<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'professional_summary' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'الاسم يجب أن يكون نصاً',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرفاً',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفاً',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 255 حرفاً',
            'job_title.string' => 'المسمى الوظيفي يجب أن يكون نصاً',
            'job_title.max' => 'المسمى الوظيفي يجب ألا يتجاوز 255 حرفاً',
            'professional_summary.string' => 'الملخص المهني يجب أن يكون نصاً',
            'avatar.string' => 'الصورة الشخصية يجب أن تكون نصاً',
            'social_links.array' => 'روابط التواصل يجب أن تكون مصفوفة',
        ];
    }
}
