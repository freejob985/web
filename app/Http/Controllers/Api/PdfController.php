<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PdfController extends Controller
{
    /**
     * Generate PDF invoice with proper Arabic support using HTML
     */
    public function generateInvoice($orderNumber)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        }

        try {
            // Generate HTML content with proper Arabic support
            $html = view('invoices.order-html', compact('order'))->render();
            
            // Return HTML with proper headers for PDF generation
            return response($html)
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', 'inline; filename="invoice-' . $orderNumber . '.html"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'خطأ في إنشاء الفاتورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF using browser print functionality
     */
    public function generatePdfPrint($orderNumber)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        }

        try {
            // Generate HTML content optimized for printing
            $html = view('invoices.order-print', compact('order'))->render();
            
            return response($html)
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', 'inline; filename="invoice-' . $orderNumber . '.html"');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'خطأ في إنشاء الفاتورة: ' . $e->getMessage()
            ], 500);
        }
    }
}
