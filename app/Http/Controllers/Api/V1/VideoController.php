<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * عرض قائمة الفيديوهات
     */
    public function index(Request $request): JsonResponse
    {
        $videos = Video::filter($request->only(['search', 'is_published']))
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => VideoResource::collection($videos),
            'meta' => [
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'per_page' => $videos->perPage(),
                'total' => $videos->total(),
            ],
        ]);
    }

    /**
     * عرض فيديو محدد
     */
    public function show(Video $video): JsonResponse
    {
        $video->increment('views');

        return response()->json([
            'success' => true,
            'data' => new VideoResource($video),
        ]);
    }

    /**
     * إنشاء فيديو جديد
     */
    public function store(StoreVideoRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('videos/thumbnails', 'public');
        }

        $video = Video::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الفيديو بنجاح',
            'data' => new VideoResource($video),
        ], 201);
    }

    /**
     * تحديث فيديو
     */
    public function update(UpdateVideoRequest $request, Video $video): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('videos/thumbnails', 'public');
        }

        $video->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الفيديو بنجاح',
            'data' => new VideoResource($video->fresh()),
        ]);
    }

    /**
     * حذف فيديو
     */
    public function destroy(Video $video): JsonResponse
    {
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الفيديو بنجاح',
        ]);
    }
}
