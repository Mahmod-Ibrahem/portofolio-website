<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $youtubeId = $this->extractYoutubeId($this->video_url);

        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'video_url'    => $this->video_url,
            'youtube_id'   => $youtubeId,
            'embed_url'    => $youtubeId
                ? "https://www.youtube.com/embed/{$youtubeId}"
                : null,
            'thumbnail'    => $this->thumbnail
                ? asset('storage/' . $this->thumbnail)
                : ($youtubeId
                    ? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg"
                    : null),
            'is_published' => $this->is_published,
            'views'        => $this->views,
            'created_at'   => $this->created_at->format('Y-m-d H:i'),
            'updated_at'   => $this->updated_at->format('Y-m-d H:i'),
        ];
    }

    /**
     * Extract YouTube video ID from various URL formats.
     */
    private function extractYoutubeId(?string $url): ?string
    {
        if (!$url) return null;

        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/|youtube\.com\/v\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
