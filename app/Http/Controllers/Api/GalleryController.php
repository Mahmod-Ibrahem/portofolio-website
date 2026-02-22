<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /* ------------------------------------------------------------------ */
    /*  LIST                                                              */
    /* ------------------------------------------------------------------ */

    public function index(Request $request): JsonResponse
    {
        $galleries = Gallery::filter($request->only(['search', 'is_active']))
            ->ordered($request->input('sort'))
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => GalleryResource::collection($galleries),
            'meta'    => [
                'current_page' => $galleries->currentPage(),
                'last_page'    => $galleries->lastPage(),
                'per_page'     => $galleries->perPage(),
                'total'        => $galleries->total(),
            ],
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  SHOW                                                              */
    /* ------------------------------------------------------------------ */

    public function show(Gallery $gallery): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new GalleryResource($gallery),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  STORE  (single OR multi-upload)                                    */
    /* ------------------------------------------------------------------ */

    public function store(StoreGalleryRequest $request): JsonResponse
    {
        $files = [];

        // Collect files — either a single `image` or `images[]`
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('image')) {
            $files = [$request->file('image')];
        }

        $created = [];
        $maxSort = Gallery::max('sort_order') ?? 0;

        foreach ($files as $file) {
            $path = $file->store('galleries', 'public');

            $created[] = Gallery::create([
                'title'      => $request->input('title'),
                'image_path' => $path,
                'alt_text'   => $request->input('alt_text'),
                'sort_order' => ++$maxSort,
                'is_active'  => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => count($created) === 1
                ? 'تم رفع الصورة بنجاح'
                : 'تم رفع ' . count($created) . ' صور بنجاح',
            'data'    => GalleryResource::collection($created),
        ], 201);
    }

    /* ------------------------------------------------------------------ */
    /*  BULK UPLOAD  (POST /api/galleries/bulk)                           */
    /* ------------------------------------------------------------------ */

    public function bulk(StoreGalleryRequest $request): JsonResponse
    {
        return $this->store($request);
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE                                                            */
    /* ------------------------------------------------------------------ */

    public function update(UpdateGalleryRequest $request, Gallery $gallery): JsonResponse
    {
        $validated = $request->validated();

        // Replace image if a new one is provided
        if ($request->hasFile('image')) {
            // Gracefully delete old file
            if ($gallery->image_path) {
                try {
                    Storage::disk('public')->delete($gallery->image_path);
                } catch (\Throwable $e) {
                    // file missing — ignore
                }
            }

            $validated['image_path'] = $request->file('image')
                ->store('galleries', 'public');
        }

        // Remove the raw `image` key so it is not mass-assigned
        unset($validated['image']);

        $gallery->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصورة بنجاح',
            'data'    => new GalleryResource($gallery->fresh()),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  DELETE                                                            */
    /* ------------------------------------------------------------------ */

    public function destroy(Gallery $gallery): JsonResponse
    {
        // Gracefully delete file
        if ($gallery->image_path) {
            try {
                Storage::disk('public')->delete($gallery->image_path);
            } catch (\Throwable $e) {
                // file missing — ignore
            }
        }

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الصورة بنجاح',
        ]);
    }
}
