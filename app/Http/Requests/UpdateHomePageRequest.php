<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $jsonFields = ['expertise_cards', 'achievements', 'volunteering_bullets', 'social_links', 'skills', 'languages', 'certifications', 'experiences', 'media_appearances'];

        foreach ($jsonFields as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $this->merge([$field => json_decode($this->input($field), true)]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'hero_badge' => 'nullable|string|max:255',
            'hero_name' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_quote' => 'nullable|string',
            'hero_image' => 'nullable|image|max:2048',
            'hero_floating_text' => 'nullable|string',
            'expertise_cards' => 'nullable|array',
            'expertise_cards.*.icon' => 'required_with:expertise_cards|string',
            'expertise_cards.*.title' => 'required_with:expertise_cards|string',
            'expertise_cards.*.desc' => 'required_with:expertise_cards|string',
            'tip_banner' => 'nullable|string',
            'achievements' => 'nullable|array',
            'achievements.*.num' => 'required_with:achievements|string',
            'achievements.*.label' => 'required_with:achievements|string',
            'consultations_text' => 'nullable|string',
            'volunteering_intro' => 'nullable|string',
            'volunteering_bullets' => 'nullable|array',
            'volunteering_bullets.*' => 'string',
            'volunteering_closing' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links|string',
            'social_links.*.url' => 'required_with:social_links|string|url',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            'languages' => 'nullable|array',
            'languages.*' => 'string',
            'certifications' => 'nullable|array',
            'certifications.*.type' => 'required_with:certifications|string',
            'certifications.*.description' => 'required_with:certifications|string',
            'experiences' => 'nullable|array',
            'experiences.*' => 'string',
            'media_appearances' => 'nullable|array',
            'media_appearances.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'hero_image.image' => 'يجب أن تكون صورة البطل من نوع صورة',
            'hero_image.max' => 'حجم صورة البطل لا يجب أن يتجاوز 2 ميجابايت',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ];
    }
}
