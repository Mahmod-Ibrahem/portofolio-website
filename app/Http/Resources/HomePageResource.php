<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hero_badge' => $this->hero_badge,
            'hero_name' => $this->hero_name,
            'hero_subtitle' => $this->hero_subtitle,
            'hero_quote' => $this->hero_quote,
            'hero_image' => $this->hero_image ? asset('storage/' . $this->hero_image) : null,
            'hero_floating_text' => $this->hero_floating_text,
            'expertise_cards' => $this->expertise_cards,
            'tip_banner' => $this->tip_banner,
            'achievements' => $this->achievements,
            'consultations_text' => $this->consultations_text,
            'volunteering_intro' => $this->volunteering_intro,
            'volunteering_bullets' => $this->volunteering_bullets,
            'volunteering_closing' => $this->volunteering_closing,
            'phone' => $this->phone,
            'email' => $this->email,
            'social_links' => $this->social_links,
            'skills' => $this->skills,
            'languages' => $this->languages,
            'certifications' => $this->certifications,
            'experiences' => $this->experiences,
            'media_appearances' => $this->media_appearances,
            'honors' => $this->honors,
            'statistics' => $this->statistics,
            'books' => $this->books,

        ];
    }
}
