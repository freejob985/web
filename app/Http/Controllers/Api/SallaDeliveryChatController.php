<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderMessage;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SallaDeliveryChatController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * إرسال رسالة جديدة
     *
     * @param Request $request
     * @param int $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request, $orderId)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:5000',
            'sender_type' => 'required|in:user,driver,admin',
            'sender_id' => 'required|integer',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        // البحث عن الطلب
        $order = Order::with(['user', 'driver'])->find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود'
            ], 404);
        }

        // معالجة المرفق إذا كان موجوداً
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('order_messages', 'public');
        }

        // إنشاء الرسالة
        $orderMessage = OrderMessage::create([
            'order_id' => $orderId,
            'sender_type' => $request->sender_type,
            'sender_id' => $request->sender_id,
            'message' => $request->message,
            'attachment' => $attachmentPath,
        ]);

        // إرسال إشعار Firebase للمستلم
        $this->sendFirebaseNotification($orderMessage, $order);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرسالة بنجاح',
            'data' => [
                'message_id' => $orderMessage->id,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'sender_type' => $orderMessage->sender_type,
                'message' => $orderMessage->message,
                'attachment' => $attachmentPath ? Storage::url($attachmentPath) : null,
                'created_at' => $orderMessage->created_at->toIso8601String(),
            ]
        ], 201);
    }

    /**
     * إرسال إشعار Firebase للمستلم
     *
     * @param OrderMessage $message
     * @param Order $order
     * @return void
     */
    protected function sendFirebaseNotification($message, $order)
    {
        $recipient = $message->getRecipient();

        if (!$recipient || !$recipient['model']) {
            return;
        }

        // إرسال الإشعار بناءً على نوع المستلم
        if ($recipient['type'] === 'driver') {
            // إرسال للسائق
            $this->firebaseService->sendNewMessageToDriver(
                $recipient['model'],
                $order,
                $message->message
            );
        } elseif ($recipient['type'] === 'user') {
            // إرسال للمستخدم/العميل
            $this->firebaseService->sendNewMessageToUser(
                $recipient['model'],
                $order,
                $message->message
            );
        }
    }

    /**
     * الحصول على جميع رسائل طلب معين
     *
     * @param int $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود'
            ], 404);
        }

        $messages = OrderMessage::where('order_id', $orderId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'sender_id' => $message->sender_id,
                    'message' => $message->message,
                    'attachment' => $message->attachment ? Storage::url($message->attachment) : null,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'messages' => $messages,
                'total_messages' => $messages->count(),
            ]
        ]);
    }

    /**
     * تحديد الرسالة كمقروءة
     *
     * @param int $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($messageId)
    {
        $message = OrderMessage::find($messageId);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'الرسالة غير موجودة'
            ], 404);
        }

        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد الرسالة كمقروءة',
            'data' => [
                'message_id' => $message->id,
                'is_read' => $message->is_read,
                'read_at' => $message->read_at->toIso8601String(),
            ]
        ]);
    }

    /**
     * الحصول على عدد الرسائل غير المقروءة
     *
     * @param int $orderId
     * @param string $recipientType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount($orderId, $recipientType)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود'
            ], 404);
        }

        // حساب الرسائل غير المقروءة حسب نوع المستلم
        $senderType = $recipientType === 'driver' ? 'user' : 'driver';

        $unreadCount = OrderMessage::where('order_id', $orderId)
            ->where('sender_type', $senderType)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $order->id,
                'recipient_type' => $recipientType,
                'unread_count' => $unreadCount,
            ]
        ]);
    }

    /**
     * تحديث Firebase token للمستخدم
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_type' => 'required|in:user,driver',
            'user_id' => 'required|integer',
            'fcm_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->user_type === 'user') {
            $user = \App\Models\User::find($request->user_id);
            if ($user) {
                $user->update(['fcm_token' => $request->fcm_token]);
            }
        } elseif ($request->user_type === 'driver') {
            $driver = \App\Models\Driver::find($request->user_id);
            if ($driver) {
                $driver->updateFcmToken($request->fcm_token);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث Firebase token بنجاح'
        ]);
    }
}
