<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Generate PDF invoice with proper Arabic support
     */
    public function generatePdf($orderNumber)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        }

        try {
            // Generate HTML content
            $html = view('invoices.order-unicode', compact('order'))->render();
            
            // Set proper headers for PDF download
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="invoice-' . $orderNumber . '.pdf"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];

            // For now, return HTML as fallback
            // In production, you would use a proper PDF library like TCPDF or mPDF
            return response($html)
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="invoice-' . $orderNumber . '.html"');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'خطأ في إنشاء الفاتورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate HTML invoice (fallback)
     */
    public function generateHtml($orderNumber)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        }

        $html = view('invoices.order-unicode', compact('order'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="invoice-' . $orderNumber . '.html"');
    }
}
