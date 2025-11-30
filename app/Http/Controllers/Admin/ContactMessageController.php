<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $messages = $query->paginate(20)->withQueryString();

        // Get statistics
        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::new()->count(),
            'read' => ContactMessage::read()->count(),
            'replied' => ContactMessage::replied()->count(),
            'closed' => ContactMessage::closed()->count(),
            'unread' => ContactMessage::unread()->count()
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    /**
     * Display read messages
     */
    public function read()
    {
        $messages = ContactMessage::read()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contact-messages.read', compact('messages'));
    }

    /**
     * Display unread messages
     */
    public function unread()
    {
        $messages = ContactMessage::unread()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contact-messages.unread', compact('messages'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage)
    {
        // Mark as read when viewed
        if ($contactMessage->status === 'new') {
            $contactMessage->markAsRead();
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'status' => 'required|in:new,read,replied,closed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $contactMessage->update($data);

        if ($request->status === 'replied') {
            $contactMessage->markAsReplied();
        }

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'تم تحديث حالة الرسالة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'تم حذف الرسالة بنجاح');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تمييز الرسالة كمقروءة'
        ]);
    }

    /**
     * Mark message as replied
     */
    public function markAsReplied(ContactMessage $contactMessage)
    {
        $contactMessage->markAsReplied();

        return response()->json([
            'success' => true,
            'message' => 'تم تمييز الرسالة كرد عليها'
        ]);
    }

    /**
     * Mark message as closed
     */
    public function markAsClosed(ContactMessage $contactMessage)
    {
        $contactMessage->markAsClosed();

        return response()->json([
            'success' => true,
            'message' => 'تم إغلاق الرسالة'
        ]);
    }

    /**
     * Get unread messages count for notifications
     */
    public function getUnreadCount()
    {
        $count = ContactMessage::new()->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Get recent messages for notifications
     */
    public function getRecentMessages()
    {
        $messages = ContactMessage::new()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
