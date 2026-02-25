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
    /*  STORE  (single image upload)                                      */
    /* ------------------------------------------------------------------ */

    public function store(StoreGalleryRequest $request): JsonResponse
    {
        $path    = $request->file('image')->store('galleries', 'public');
        $maxSort = Gallery::max('sort_order') ?? 0;

        $gallery = Gallery::create([
            'title'      => $request->input('title'),
            'image_path' => $path,
            'alt_text'   => $request->input('alt_text'),
            'sort_order' => $maxSort + 1,
            'is_active'  => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم رفع الصورة بنجاح',
            'data'    => new GalleryResource($gallery),
        ], 201);
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE                                                            */
    /* ------------------------------------------------------------------ */

    public function update(UpdateGalleryRequest $request, Gallery $gallery): JsonResponse
    {
        $validated = $request->validated();

        // Replace image if a new one is provided
        if ($request->hasFile('image')) {
            // Delete old file gracefully
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
        // Delete file gracefully
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
