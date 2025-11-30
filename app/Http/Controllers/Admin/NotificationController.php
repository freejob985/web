<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::where('is_read', false)->count(),
            'read' => Notification::where('is_read', true)->count(),
            'orders' => Notification::where('type', 'order')->count(),
            'contacts' => Notification::where('type', 'contact')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    public function read()
    {
        $notifications = Notification::where('is_read', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.read', compact('notifications'));
    }

    public function unread()
    {
        $notifications = Notification::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.unread', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        // Mark as read when viewing
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('admin.notifications.show', compact('notification'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تمييز الإشعار كمقروء'
        ]);
    }

    public function markAsUnread(Notification $notification)
    {
        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'تم تمييز الإشعار كغير مقروء'
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تمييز جميع الإشعارات كمقروءة'
        ]);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإشعار بنجاح'
        ]);
    }

    public function getUnreadCount()
    {
        $count = Notification::where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }

    public function getRecent()
    {
        $notifications = Notification::with([])
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'time_ago' => $notification->time_ago,
                    'data' => $notification->data
                ];
            });

        return response()->json($notifications);
    }

    public function getStats()
    {
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::where('is_read', false)->count(),
            'orders' => Notification::where('type', 'order')->where('is_read', false)->count(),
            'contacts' => Notification::where('type', 'contact')->where('is_read', false)->count(),
        ];

        return response()->json($stats);
    }
}