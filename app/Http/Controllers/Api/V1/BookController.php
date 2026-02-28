<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class   BookController extends Controller
{
    /**
     * عرض قائمة الكتب
     */
    public function index(Request $request): JsonResponse
    {
        $books = Book::filter($request->only(['is_published']))
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => BookResource::collection($books),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ],
        ]);
    }

    /**
     * عرض كتاب محدد
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new BookResource($book),
        ]);
    }

    /**
     * إنشاء كتاب جديد
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('books', 'public');
        }

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الكتاب بنجاح',
            'data' => new BookResource($book),
        ], 201);
    }

    /**
     * تحديث كتاب
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $validated['image'] = $request->file('image')
                ->store('books', 'public');
        }

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الكتاب بنجاح',
            'data' => new BookResource($book->fresh()),
        ]);
    }

    /**
     * حذف كتاب
     */
    public function destroy(Book $book): JsonResponse
    {
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الكتاب بنجاح',
        ]);
    }
}
