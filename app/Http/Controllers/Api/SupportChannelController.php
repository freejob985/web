<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportChannel;
use Illuminate\Http\Request;

class SupportChannelController extends Controller
{
    /**
     * Get all active support channels
     */
    public function index()
    {
        $supportChannels = SupportChannel::active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $supportChannels
        ]);
    }

    /**
     * Get a specific support channel
     */
    public function show(SupportChannel $supportChannel)
    {
        if (!$supportChannel->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'قناة الدعم غير متاحة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $supportChannel
        ]);
    }
}
