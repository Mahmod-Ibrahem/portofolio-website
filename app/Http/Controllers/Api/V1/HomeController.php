<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHomePageRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\BookResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\HomePageResource;
use App\Http\Resources\VideoResource;
use App\Models\Article;
use App\Models\Book;
use App\Models\Gallery;
use App\Models\HomePage;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Get the singleton homepage data.
     * If no record exists yet, default values are seeded automatically.
     */
    public function singleton()
    {
        $page = HomePage::singleton();

        return new HomePageResource($page);
    }

    /**
     * Single endpoint that returns ALL data needed by the homepage.
     */
    public function homepageData(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        // 1) Singleton page settings
        $page = HomePage::singleton();

        // 2) Articles – paginated
        $articlesPage = (int) $request->input('articles_page', 1);
        $articles = Article::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(6, ['*'], 'articles_page', $articlesPage);

        // 3) Videos – paginated
        $videosPage = (int) $request->input('videos_page', 1);
        $videos = Video::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(6, ['*'], 'videos_page', $videosPage);

        // 4) Gallery – ALL, no pagination
        $galleries = Gallery::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // 5) Books – ALL, no pagination
        $books = Book::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'page' => new HomePageResource($page),
                'articles' => [
                    'data' => ArticleResource::collection($articles),
                    'meta' => [
                        'current_page' => $articles->currentPage(),
                        'last_page' => $articles->lastPage(),
                        'per_page' => $articles->perPage(),
                        'total' => $articles->total(),
                    ],
                ],
                'videos' => [
                    'data' => VideoResource::collection($videos),
                    'meta' => [
                        'current_page' => $videos->currentPage(),
                        'last_page' => $videos->lastPage(),
                        'per_page' => $videos->perPage(),
                        'total' => $videos->total(),
                    ],
                ],
                'galleries' => GalleryResource::collection($galleries),
                'books' => BookResource::collection($books),
            ],
        ]);
    }

    /**
     * Update the singleton homepage data.
     */
    public function updateSingleton(UpdateHomePageRequest $request)
    {
        $page = HomePage::singleton();
        $validated = $request->validated();

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            if ($page->hero_image) {
                Storage::disk('public')->delete($page->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')
                ->store('homepage', 'public');
        }

        $page->update($validated);

        return new HomePageResource($page->fresh());
    }
}
