<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request)
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'notifications' => $notifications->items(),
                'unread_count' => auth()->user()->unreadNotifications->count()
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    }

    /**
     * Get recent notifications (for dropdown)
     */
    public function recent()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->unreadNotifications->count()
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id, Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            
            // If the request expects JSON, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'unread_count' => auth()->user()->unreadNotifications->count()
                ]);
            }
            
            // Otherwise, redirect to the action_url in the notification data
            $actionUrl = $notification->data['action_url'] ?? route('dashboard');
            return redirect($actionUrl);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        return redirect()->back()->with('error', 'Notification not found');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete single notification
     */
    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Clear all read notifications
     */
    public function clearRead()
    {
        auth()->user()
            ->readNotifications()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Read notifications cleared'
        ]);
    }
}
