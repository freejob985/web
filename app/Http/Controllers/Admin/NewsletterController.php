<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = Newsletter::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by email or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.newsletters.index', compact('subscribers'));
    }

    public function campaigns()
    {
        $campaigns = NewsletterCampaign::latest()->paginate(20);
        return view('admin.newsletters.campaigns', compact('campaigns'));
    }

    public function createCampaign()
    {
        return view('admin.newsletters.create-campaign');
    }

    public function storeCampaign(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'html_content' => 'nullable|string',
            'scheduled_at' => 'nullable|date|after:now'
        ]);

        $campaign = NewsletterCampaign::create($data);

        return redirect()->route('admin.newsletters.campaigns')
            ->with('success', 'تم إنشاء الحملة بنجاح');
    }

    public function sendCampaign(NewsletterCampaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return back()->with('error', 'لا يمكن إرسال هذه الحملة');
        }

        $campaign->startSending();

        // Get active subscribers
        $subscribers = Newsletter::active()->get();

        if ($subscribers->isEmpty()) {
            $campaign->markAsFailed();
            return back()->with('error', 'لا يوجد مشتركين نشطين');
        }

        // Send emails (in a real application, you'd use a queue)
        $sentCount = 0;
        $failedCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::send('emails.newsletter', [
                    'campaign' => $campaign,
                    'subscriber' => $subscriber
                ], function ($message) use ($campaign, $subscriber) {
                    $message->to($subscriber->email, $subscriber->name)
                           ->subject($campaign->subject);
                });

                // Log successful send
                $campaign->logs()->create([
                    'newsletter_id' => $subscriber->id,
                    'status' => 'sent',
                    'sent_at' => now()
                ]);

                $sentCount++;
            } catch (\Exception $e) {
                // Log failed send
                $campaign->logs()->create([
                    'newsletter_id' => $subscriber->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'sent_at' => now()
                ]);

                $failedCount++;
            }
        }

        $campaign->update([
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'status' => 'sent',
            'sent_at' => now()
        ]);

        return back()->with('success', "تم إرسال الحملة بنجاح. تم إرسال {$sentCount} رسالة، فشل {$failedCount} رسالة");
    }

    public function showCampaign(NewsletterCampaign $campaign)
    {
        $campaign->load('logs.newsletter');
        return view('admin.newsletters.show-campaign', compact('campaign'));
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'تم حذف المشترك بنجاح');
    }

    public function unsubscribe(Newsletter $newsletter)
    {
        $newsletter->unsubscribe();

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الاشتراك بنجاح'
        ]);
    }

    public function resubscribe(Newsletter $newsletter)
    {
        $newsletter->resubscribe();

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة الاشتراك بنجاح'
        ]);
    }

    public function stats()
    {
        $totalSubscribers = Newsletter::count();
        $activeSubscribers = Newsletter::active()->count();
        $unsubscribedSubscribers = Newsletter::unsubscribed()->count();
        $totalCampaigns = NewsletterCampaign::count();
        $sentCampaigns = NewsletterCampaign::sent()->count();
        $totalEmailsSent = NewsletterLog::sent()->count();

        return response()->json([
            'total_subscribers' => $totalSubscribers,
            'active_subscribers' => $activeSubscribers,
            'unsubscribed_subscribers' => $unsubscribedSubscribers,
            'total_campaigns' => $totalCampaigns,
            'sent_campaigns' => $sentCampaigns,
            'total_emails_sent' => $totalEmailsSent
        ]);
    }
}