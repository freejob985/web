<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم يجب أن يكون أقل من 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني يجب أن يكون أقل من 255 حرف',
            'phone.max' => 'رقم الهاتف يجب أن يكون أقل من 20 رقم',
            'subject.required' => 'الموضوع مطلوب',
            'subject.max' => 'الموضوع يجب أن يكون أقل من 255 حرف',
            'message.required' => 'الرسالة مطلوبة',
            'message.max' => 'الرسالة يجب أن تكون أقل من 2000 حرف'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تصحيح الأخطاء التالية',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contactMessage = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.',
                'data' => [
                    'id' => $contactMessage->id,
                    'subject' => $contactMessage->subject,
                    'created_at' => $contactMessage->formatted_created_at
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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

        $messages = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function show(ContactMessage $contactMessage)
    {
        // Mark as read when viewed
        if ($contactMessage->status === 'new') {
            $contactMessage->markAsRead();
        }

        return response()->json([
            'success' => true,
            'data' => $contactMessage
        ]);
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,read,replied,closed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $contactMessage->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        if ($request->status === 'replied') {
            $contactMessage->markAsReplied();
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الرسالة بنجاح',
            'data' => $contactMessage
        ]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الرسالة بنجاح'
        ]);
    }

    public function getStats()
    {
        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::new()->count(),
            'read' => ContactMessage::read()->count(),
            'replied' => ContactMessage::replied()->count(),
            'closed' => ContactMessage::closed()->count(),
            'unread' => ContactMessage::unread()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
