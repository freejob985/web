<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ErrorLogController extends Controller
{
    public function index(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        $errors = [];
        
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            $errors = $this->parseLogFile($logContent);
        }
        
        // Filter by date if provided
        if ($request->has('date')) {
            $filterDate = Carbon::parse($request->date)->format('Y-m-d');
            $errors = array_filter($errors, function($error) use ($filterDate) {
                return strpos($error['date'], $filterDate) !== false;
            });
        }
        
        // Filter by level if provided
        if ($request->has('level')) {
            $errors = array_filter($errors, function($error) use ($request) {
                return strtolower($error['level']) === strtolower($request->level);
            });
        }
        
        // Search in message if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $errors = array_filter($errors, function($error) use ($searchTerm) {
                return stripos($error['message'], $searchTerm) !== false;
            });
        }
        
        // Sort by date (newest first)
        usort($errors, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        // Convert to Collection for better handling
        $errorsCollection = collect($errors);
        
        // Calculate stats
        $stats = [
            'total' => $errorsCollection->count(),
            'warnings' => $errorsCollection->where('level', 'WARNING')->count(),
            'info' => $errorsCollection->where('level', 'INFO')->count(),
            'debug' => $errorsCollection->where('level', 'DEBUG')->count(),
            'errors' => $errorsCollection->where('level', 'ERROR')->count(),
        ];
        
        // Paginate
        $perPage = 50;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedErrors = $errorsCollection->slice($offset, $perPage);
        
        $totalErrors = $errorsCollection->count();
        $totalPages = ceil($totalErrors / $perPage);
        
        return view('admin.errors.index', compact(
            'paginatedErrors', 
            'totalErrors', 
            'totalPages', 
            'currentPage',
            'perPage',
            'stats'
        ));
    }
    
    public function show($id)
    {
        $logPath = storage_path('logs/laravel.log');
        $errors = [];
        
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            $errors = $this->parseLogFile($logContent);
        }
        
        $error = $errors[$id] ?? null;
        
        if (!$error) {
            return redirect()->route('admin.errors.index')
                ->with('error', 'الخطأ غير موجود');
        }
        
        return view('admin.errors.show', compact('error'));
    }
    
    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }
        
        return redirect()->route('admin.errors.index')
            ->with('success', 'تم مسح ملف الأخطاء بنجاح');
    }
    
    public function download()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            return redirect()->route('admin.errors.index')
                ->with('error', 'ملف الأخطاء غير موجود');
        }
        
        return response()->download($logPath, 'laravel-errors-' . date('Y-m-d-H-i-s') . '.log');
    }
    
    private function parseLogFile($content)
    {
        $errors = [];
        $lines = explode("\n", $content);
        $currentError = null;
        
        foreach ($lines as $line) {
            // Check if this is a new error entry
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] local\.(\w+): (.+)$/', $line, $matches)) {
                // Save previous error if exists
                if ($currentError) {
                    $errors[] = $currentError;
                }
                
                // Start new error
                $currentError = [
                    'date' => $matches[1],
                    'level' => strtoupper($matches[2]),
                    'message' => $matches[3],
                    'stack_trace' => '',
                    'context' => []
                ];
            } elseif ($currentError) {
                // Add to current error
                if (strpos($line, 'Stack trace:') !== false) {
                    $currentError['stack_trace'] .= $line . "\n";
                } elseif (strpos($line, 'Context:') !== false) {
                    // Parse context
                    $contextLine = trim($line);
                    if (strpos($contextLine, 'Context:') === 0) {
                        $contextLine = trim(substr($contextLine, 8));
                        if (!empty($contextLine)) {
                            $currentError['context'][] = $contextLine;
                        }
                    }
                } elseif (!empty(trim($line))) {
                    $currentError['stack_trace'] .= $line . "\n";
                }
            }
        }
        
        // Add last error if exists
        if ($currentError) {
            $errors[] = $currentError;
        }
        
        return $errors;
    }
    
    public function getStats()
    {
        $logPath = storage_path('logs/laravel.log');
        $errors = [];
        
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            $errors = $this->parseLogFile($logContent);
        }
        
        $stats = [
            'total' => count($errors),
            'by_level' => [],
            'by_date' => [],
            'recent' => array_slice($errors, 0, 10)
        ];
        
        foreach ($errors as $error) {
            // Count by level
            $level = $error['level'];
            $stats['by_level'][$level] = ($stats['by_level'][$level] ?? 0) + 1;
            
            // Count by date
            $date = substr($error['date'], 0, 10);
            $stats['by_date'][$date] = ($stats['by_date'][$date] ?? 0) + 1;
        }
        
        return response()->json($stats);
    }
}
