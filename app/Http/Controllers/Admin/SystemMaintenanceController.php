<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SystemMaintenanceController extends Controller
{
    /**
     * عرض صفحة الصيانة
     */
    public function index()
    {
        // جلب معلومات النظام
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'storage_path' => storage_path(),
            'cache_path' => storage_path('framework/cache'),
            'logs_path' => storage_path('logs'),
        ];

        // حساب أحجام المخلفات
        $cacheSizes = $this->getCacheSizes();
        
        // جلب جميع routes الـ API
        $apiRoutes = $this->getApiRoutes();
        
        // جلب أحدث log files
        $logFiles = $this->getLogFiles();

        return view('admin.system.maintenance', compact('systemInfo', 'cacheSizes', 'apiRoutes', 'logFiles'));
    }

    /**
     * مسح الكاش بالكامل
     */
    public function clearAllCache()
    {
        try {
            // مسح جميع أنواع الكاش
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('event:clear');
            
            // محاولة مسح compiled classes
            if (file_exists(base_path('bootstrap/cache/compiled.php'))) {
                @unlink(base_path('bootstrap/cache/compiled.php'));
            }
            
            // محاولة مسح services cache
            if (file_exists(base_path('bootstrap/cache/services.php'))) {
                @unlink(base_path('bootstrap/cache/services.php'));
            }

            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم مسح جميع المخلفات بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * مسح كاش التطبيق فقط
     */
    public function clearApplicationCache()
    {
        try {
            Artisan::call('cache:clear');
            Cache::flush();
            
            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم مسح كاش التطبيق بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * مسح كاش الإعدادات
     */
    public function clearConfigCache()
    {
        try {
            Artisan::call('config:clear');
            
            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم مسح كاش الإعدادات بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * مسح كاش الروتات
     */
    public function clearRouteCache()
    {
        try {
            Artisan::call('route:clear');
            
            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم مسح كاش الروتات بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * مسح كاش الـ Views
     */
    public function clearViewCache()
    {
        try {
            Artisan::call('view:clear');
            
            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم مسح كاش الـ Views بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * مسح ملفات الـ Logs
     */
    public function clearLogs()
    {
        try {
            $logsPath = storage_path('logs');
            $files = File::glob($logsPath . '/*.log');
            
            $deletedCount = 0;
            foreach ($files as $file) {
                if (File::exists($file)) {
                    File::delete($file);
                    $deletedCount++;
                }
            }
            
            return redirect()->route('admin.system.maintenance')
                ->with('success', "تم مسح {$deletedCount} ملف log بنجاح");
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * مسح Sessions
     */
    public function clearSessions()
    {
        try {
            // مسح sessions من الملفات
            $sessionsPath = storage_path('framework/sessions');
            if (File::exists($sessionsPath)) {
                $files = File::glob($sessionsPath . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        File::delete($file);
                    }
                }
            }
            
            // مسح sessions من قاعدة البيانات إذا كانت مستخدمة
            if (config('session.driver') === 'database') {
                DB::table(config('session.table', 'sessions'))->truncate();
            }
            
            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم مسح جميع الـ Sessions بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * تحسين النظام (optimize)
     */
    public function optimizeSystem()
    {
        try {
            Artisan::call('optimize:clear');
            Artisan::call('optimize');
            
            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم تحسين النظام بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * حساب أحجام المخلفات
     */
    private function getCacheSizes()
    {
        $sizes = [
            'cache' => $this->getDirectorySize(storage_path('framework/cache')),
            'views' => $this->getDirectorySize(storage_path('framework/views')),
            'sessions' => $this->getDirectorySize(storage_path('framework/sessions')),
            'logs' => $this->getDirectorySize(storage_path('logs')),
        ];

        return $sizes;
    }

    /**
     * حساب حجم مجلد
     */
    private function getDirectorySize($path)
    {
        if (!File::exists($path)) {
            return '0 B';
        }

        $size = 0;
        $files = File::allFiles($path);
        
        foreach ($files as $file) {
            $size += $file->getSize();
        }

        return $this->formatBytes($size);
    }

    /**
     * تحويل bytes إلى format قابل للقراءة
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * جلب جميع API routes
     */
    private function getApiRoutes()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return strpos($route->uri(), 'api/') === 0;
        })->map(function ($route) {
            $methods = implode('|', $route->methods());
            
            return [
                'methods' => $methods,
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => implode(', ', $route->middleware()),
            ];
        })->sortBy('uri')->values();

        return $routes;
    }

    /**
     * جلب ملفات الـ Logs
     */
    private function getLogFiles()
    {
        $logsPath = storage_path('logs');
        $files = File::glob($logsPath . '/*.log');
        
        $logs = [];
        foreach ($files as $file) {
            $logs[] = [
                'name' => basename($file),
                'size' => $this->formatBytes(File::size($file)),
                'modified' => date('Y-m-d H:i:s', File::lastModified($file)),
                'path' => $file,
            ];
        }

        // ترتيب حسب آخر تعديل
        usort($logs, function ($a, $b) {
            return strcmp($b['modified'], $a['modified']);
        });

        return $logs;
    }

    /**
     * عرض محتوى ملف log
     */
    public function viewLog($filename)
    {
        try {
            $filePath = storage_path('logs/' . $filename);
            
            if (!File::exists($filePath)) {
                return redirect()->route('admin.system.maintenance')
                    ->with('error', 'الملف غير موجود');
            }

            // قراءة آخر 500 سطر من الملف
            $content = $this->tailFile($filePath, 500);

            return view('admin.system.log-viewer', compact('filename', 'content'));
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * قراءة آخر n سطر من ملف
     */
    private function tailFile($file, $lines = 100)
    {
        $handle = fopen($file, "r");
        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = [];
        
        while ($linecounter > 0) {
            $t = " ";
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }
            $linecounter--;
            if ($beginning) {
                rewind($handle);
            }
            $text[$lines - $linecounter - 1] = fgets($handle);
            if ($beginning) break;
        }
        fclose($handle);
        
        return array_reverse($text);
    }

    /**
     * تنزيل ملف log
     */
    public function downloadLog($filename)
    {
        $filePath = storage_path('logs/' . $filename);
        
        if (!File::exists($filePath)) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'الملف غير موجود');
        }

        return response()->download($filePath);
    }

    /**
     * حذف ملف log محدد
     */
    public function deleteLog($filename)
    {
        try {
            $filePath = storage_path('logs/' . $filename);
            
            if (!File::exists($filePath)) {
                return redirect()->route('admin.system.maintenance')
                    ->with('error', 'الملف غير موجود');
            }

            File::delete($filePath);

            return redirect()->route('admin.system.maintenance')
                ->with('success', 'تم حذف ملف الـ log بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}

