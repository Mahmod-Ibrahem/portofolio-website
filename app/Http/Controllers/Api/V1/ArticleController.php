<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * عرض قائمة المقالات
     */
    public function index(Request $request): JsonResponse
    {
        $articles = Article::filter($request->only(['search', 'is_published']))
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => ArticleResource::collection($articles),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    /**
     * عرض مقالة محددة
     */
    public function show(Article $article): JsonResponse
    {
        $article->increment('views');

        return response()->json([
            'success' => true,
            'data' => new ArticleResource($article),
        ]);
    }

    /**
     * إنشاء مقالة جديدة
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('articles/covers', 'public');
        }

        $article = Article::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء المقالة بنجاح',
            'data' => new ArticleResource($article),
        ], 201);
    }

    /**
     * تحديث مقالة
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($article->cover_image) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')
                ->store('articles/covers', 'public');
        }

        $article->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المقالة بنجاح',
            'data' => new ArticleResource($article->fresh()),
        ]);
    }

    /**
     * حذف مقالة
     */
    public function destroy(Article $article): JsonResponse
    {
        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المقالة بنجاح',
        ]);
    }
}
