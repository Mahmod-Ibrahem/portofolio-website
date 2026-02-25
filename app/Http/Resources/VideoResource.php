<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $youtubeId = $this->extractYoutubeId($this->video_url);
        $driveId   = $this->extractDriveId($this->video_url);

        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'video_url'   => $this->video_url,

            'youtube_id'  => $youtubeId,
            'drive_id'    => $driveId,

            'embed_url'   => $youtubeId
                ? "https://www.youtube.com/embed/{$youtubeId}"
                : ($driveId
                    ? "https://drive.google.com/file/d/{$driveId}/preview"
                    : null),

            'thumbnail'   => $this->thumbnail
                ? asset('storage/' . $this->thumbnail)
                : ($youtubeId
                    ? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg"
                    : null),

            'is_published' => $this->is_published,
            'views'       => $this->views,
            'created_at'  => $this->created_at->format('Y-m-d H:i'),
            'updated_at'  => $this->updated_at->format('Y-m-d H:i'),
        ];
    }

    private function extractYoutubeId(?string $url): ?string
    {
        if (!$url) return null;

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/|youtube\.com\/v\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function extractDriveId(?string $url): ?string
    {
        if (!$url) return null;

        // Pattern for /file/d/FILE_ID/
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Pattern for ?id=FILE_ID
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
