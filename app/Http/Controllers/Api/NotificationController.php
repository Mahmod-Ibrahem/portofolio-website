<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\LetterStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get late letters notifications.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get user notifications (read and unread, limited to 50)
        $notifications = $request->user()->notifications()->limit(50)->get()->map(function ($n) {
            $data = $n->data;

            // Calculate overdue days dynamically if possible, or use stored value
            // We stored 'date' and 'deadline_days' in notification data
            $overdueDays = 0;
            if (isset($data['date']) && isset($data['deadline_days'])) {
                $deadlineDate = \Carbon\Carbon::parse($data['date'])->addDays((int)$data['deadline_days']);
                $overdueDays = $deadlineDate->isPast() ? (int)$deadlineDate->diffInDays(now()) : 0;
            }

            return [
                'id' => $data['letter_id'] ?? null, // Map letter_id to id for frontend compatibility
                'notification_id' => $n->id,
                'title' => $data['title'] ?? 'بدون موضوع',
                'category' => $data['category'] ?? 'بدون تصنيف',
                'letter_number' => $data['letter_number'] ?? '',
                'deadline_days' => $data['deadline_days'] ?? 0,
                'overdue_days' => $overdueDays,
                'date' => $data['date'] ?? null,
                'created_at' => $n->created_at->format('Y-m-d H:i'),
                'read_at' => $n->read_at,
                // Status color might be hardcoded or derived
                'status_color' => '#ef4444',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
