<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:newsletters,email',
            'name' => 'nullable|string|max:255'
        ]);

        $newsletter = Newsletter::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'status' => 'active',
            'subscribed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم الاشتراك في النشرة الإخبارية بنجاح',
            'subscriber' => [
                'id' => $newsletter->id,
                'email' => $newsletter->email,
                'name' => $newsletter->name,
                'subscribed_at' => $newsletter->subscribed_at->toDateTimeString()
            ]
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:newsletters,email'
        ]);

        $newsletter = Newsletter::where('email', $data['email'])->first();

        if (!$newsletter) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني غير مسجل في النشرة الإخبارية'
            ], 404);
        }

        $newsletter->unsubscribe();

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الاشتراك في النشرة الإخبارية بنجاح'
        ]);
    }

    public function resubscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:newsletters,email'
        ]);

        $newsletter = Newsletter::where('email', $data['email'])->first();

        if (!$newsletter) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني غير مسجل في النشرة الإخبارية'
            ], 404);
        }

        $newsletter->resubscribe();

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة الاشتراك في النشرة الإخبارية بنجاح'
        ]);
    }

    public function checkSubscription(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);

        $newsletter = Newsletter::where('email', $data['email'])->first();

        if (!$newsletter) {
            return response()->json([
                'success' => true,
                'subscribed' => false,
                'message' => 'البريد الإلكتروني غير مسجل في النشرة الإخبارية'
            ]);
        }

        return response()->json([
            'success' => true,
            'subscribed' => $newsletter->status === 'active',
            'subscriber' => [
                'id' => $newsletter->id,
                'email' => $newsletter->email,
                'name' => $newsletter->name,
                'status' => $newsletter->status,
                'subscribed_at' => $newsletter->subscribed_at->toDateTimeString()
            ]
        ]);
    }

    public function stats()
    {
        $totalSubscribers = Newsletter::count();
        $activeSubscribers = Newsletter::active()->count();
        $unsubscribedSubscribers = Newsletter::unsubscribed()->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_subscribers' => $totalSubscribers,
                'active_subscribers' => $activeSubscribers,
                'unsubscribed_subscribers' => $unsubscribedSubscribers
            ]
        ]);
    }
}