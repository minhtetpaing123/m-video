<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display notifications page
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            $notifications = Notification::where('user_id', $user->id)
                ->with(['fromUser', 'post'])
                ->orderBy('created_at', 'desc')
                ->get();

            $unreadCount = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();

            return view('noti', compact('notifications', 'unreadCount'));

        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            
            $notifications = collect([]);
            $unreadCount = 0;
            return view('noti', compact('notifications', 'unreadCount'));
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        try {
            if ($notification->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized'
                ], 403);
            }

            $notification->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking notification: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to mark notification'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking all: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to mark all'
            ], 500);
        }
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        try {
            if ($notification->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized'
                ], 403);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to delete notification'
            ], 500);
        }
    }

    /**
     * Get unread count for API
     */
    public function unreadCount()
    {
        try {
            $count = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'count' => 0
            ], 500);
        }
    }
}