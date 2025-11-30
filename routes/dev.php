<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

// 1. روت لجلب هياكل جداول قاعدة البيانات وتحويلها لـ JSON
Route::get('/dev/database-schema', function () {
    try {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $tables = [];
        
        if ($driver === 'sqlite') {
            // SQLite
            $tableNames = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            
            foreach ($tableNames as $table) {
                $tableName = $table->name;
                $columns = DB::select("PRAGMA table_info({$tableName})");
                
                $tableInfo = [
                    'name' => $tableName,
                    'columns' => []
                ];
                
                foreach ($columns as $column) {
                    $tableInfo['columns'][] = [
                        'name' => $column->name,
                        'type' => $column->type,
                        'notnull' => (bool)$column->notnull,
                        'default' => $column->dflt_value,
                        'pk' => (bool)$column->pk
                    ];
                }
                
                $tables[] = $tableInfo;
            }
        } elseif ($driver === 'mysql') {
            // MySQL
            $database = config('database.connections.mysql.database');
            $tableNames = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [$database]);
            
            foreach ($tableNames as $table) {
                $tableName = $table->TABLE_NAME;
                $columns = DB::select("
                    SELECT 
                        COLUMN_NAME as name,
                        DATA_TYPE as type,
                        IS_NULLABLE as nullable,
                        COLUMN_DEFAULT as default_value,
                        COLUMN_KEY as key_type,
                        EXTRA as extra
                    FROM information_schema.COLUMNS 
                    WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
                    ORDER BY ORDINAL_POSITION
                ", [$database, $tableName]);
                
                $tableInfo = [
                    'name' => $tableName,
                    'columns' => []
                ];
                
                foreach ($columns as $column) {
                    $tableInfo['columns'][] = [
                        'name' => $column->name,
                        'type' => $column->type,
                        'nullable' => $column->nullable === 'YES',
                        'default' => $column->default_value,
                        'key' => $column->key_type,
                        'extra' => $column->extra
                    ];
                }
                
                $tables[] = $tableInfo;
            }
        }
        
        return response()->json([
            'success' => true,
            'driver' => $driver,
            'tables' => $tables,
            'count' => count($tables)
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// 2. روت لرفع ملف JSON وعرض الجداول والأعمدة
Route::post('/dev/upload-schema', function (Request $request) {
    try {
        $request->validate([
            'schema_file' => 'required|file|mimes:json|max:10240'
        ]);
        
        $file = $request->file('schema_file');
        $content = file_get_contents($file->getPathname());
        $schema = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'error' => 'ملف JSON غير صالح'
            ], 400);
        }
        
        // حفظ الملف في storage
        $filename = 'schema_' . time() . '.json';
        Storage::put('schemas/' . $filename, $content);
        
        return response()->json([
            'success' => true,
            'filename' => $filename,
            'schema' => $schema
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// 3. روت لتنفيذ الاستعلامات SQL
Route::post('/dev/execute-query', function (Request $request) {
    try {
        $request->validate([
            'query' => 'required|string'
        ]);
        
        $query = $request->input('query');
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        
        // تنظيف الاستعلام
        $query = trim($query);
        
        // تحديد نوع الاستعلام
        $queryType = strtoupper(explode(' ', $query)[0]);
        
        if (in_array($queryType, ['SELECT', 'SHOW', 'DESCRIBE', 'EXPLAIN'])) {
            // استعلامات القراءة
            $results = DB::select($query);
            
            return response()->json([
                'success' => true,
                'type' => 'select',
                'results' => $results,
                'count' => count($results)
            ]);
            
        } elseif (in_array($queryType, ['INSERT', 'UPDATE', 'DELETE', 'CREATE', 'ALTER', 'DROP'])) {
            // استعلامات الكتابة
            $affected = DB::statement($query);
            
            return response()->json([
                'success' => true,
                'type' => 'modify',
                'affected_rows' => $affected,
                'message' => 'تم تنفيذ الاستعلام بنجاح'
            ]);
            
        } else {
            return response()->json([
                'success' => false,
                'error' => 'نوع الاستعلام غير مدعوم'
            ], 400);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// 4. روت لحذف ملفات الـ schema المحفوظة
Route::delete('/dev/delete-schema/{filename}', function ($filename) {
    try {
        if (Storage::exists('schemas/' . $filename)) {
            Storage::delete('schemas/' . $filename);
            return response()->json(['success' => true, 'message' => 'تم حذف الملف بنجاح']);
        }
        
        return response()->json(['success' => false, 'error' => 'الملف غير موجود'], 404);
        
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

// 5. روت لعرض الواجهة الرئيسية
Route::get('/dev/database-manager', function () {
    return view('dev.database-manager');
});

// 6. روت لجلب الملفات المحفوظة
Route::get('/dev/saved-schemas', function () {
    try {
        $files = Storage::files('schemas');
        $schemas = [];
        
        foreach ($files as $file) {
            $content = Storage::get($file);
            $schema = json_decode($content, true);
            $schemas[] = [
                'filename' => basename($file),
                'created_at' => Storage::lastModified($file),
                'tables_count' => count($schema['tables'] ?? [])
            ];
        }
        
        return response()->json(['success' => true, 'schemas' => $schemas]);
        
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

// 7. روت لجلب ملف schema محفوظ معين
Route::get('/dev/saved-schemas/{filename}', function ($filename) {
    try {
        if (Storage::exists('schemas/' . $filename)) {
            $content = Storage::get('schemas/' . $filename);
            $schema = json_decode($content, true);
            
            return response()->json([
                'success' => true,
                'schema' => $schema
            ]);
        }
        
        return response()->json(['success' => false, 'error' => 'الملف غير موجود'], 404);
        
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});
