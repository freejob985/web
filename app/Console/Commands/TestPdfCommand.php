<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;

class TestPdfCommand extends Command
{
    protected $signature = 'test:pdf';
    protected $description = 'Test PDF generation with Arabic support';

    public function handle()
    {
        // Test data
        $order = (object) [
            'order_number' => 'ORD-2025-000025',
            'created_at' => now(),
            'user' => (object) [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '+96512345678'
            ],
            'delivery_address' => 'شارع الخليج العربي، قطعة 1، شارع 15',
            'delivery_city' => 'الكويت',
            'delivery_governorate' => 'محافظة العاصمة',
            'delivery_phone' => '+96598765432',
            'status' => 'confirmed',
            'status_label' => 'مؤكد',
            'subtotal' => 25.500,
            'delivery_fee' => 2.000,
            'tax_amount' => 4.125,
            'discount_amount' => 0.000,
            'total_amount' => 31.625,
            'items' => [
                (object) [
                    'product' => (object) ['name' => 'خبز عربي طازج'],
                    'quantity' => 2,
                    'price' => 0.500
                ],
                (object) [
                    'product' => (object) ['name' => 'حليب طازج 1 لتر'],
                    'quantity' => 3,
                    'price' => 1.200
                ],
                (object) [
                    'product' => (object) ['name' => 'جبنة شيدر 500 جرام'],
                    'quantity' => 1,
                    'price' => 2.500
                ]
            ]
        ];

        try {
            $this->info('Generating PDF with Arabic support...');
            
            // Generate PDF
            $pdf = Pdf::loadView('invoices.order-unicode', compact('order'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'Arial Unicode MS',
                    'isPhpEnabled' => false,
                    'isFontSubsettingEnabled' => false,
                    'isUnicode' => true,
                    'enable_remote' => true,
                    'font_height_ratio' => 1.3,
                    'dpi' => 150,
                    'enable_font_subsetting' => false,
                    'defaultMediaType' => 'print',
                    'isJavascriptEnabled' => false,
                    'debugKeepTemp' => false,
                    'debugCss' => false,
                    'debugLayout' => false,
                    'debugLayoutLines' => false,
                    'debugLayoutBlocks' => false,
                    'debugLayoutInline' => false,
                    'debugLayoutPaddingBox' => false,
                    'fontCache' => storage_path('fonts/'),
                    'tempDir' => sys_get_temp_dir(),
                    'chroot' => public_path(),
                    'logOutputFile' => storage_path('logs/dompdf.log'),
                    'fontDir' => storage_path('fonts/'),
                    'isFontSubsettingEnabled' => false,
                    'enable_font_subsetting' => false,
                    'font_subsetting' => false
                ]);

            // Save PDF
            $pdf->save(public_path('test-invoice.pdf'));
            
            $this->info('PDF generated successfully!');
            $this->info('File saved to: public/test-invoice.pdf');
            $this->info('File size: ' . filesize(public_path('test-invoice.pdf')) . ' bytes');
            
        } catch (\Exception $e) {
            $this->error('Error generating PDF: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
