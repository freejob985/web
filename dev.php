<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Database Development Tools
|--------------------------------------------------------------------------
| 
| هذا الملف يحتوي على أدوات تطوير قاعدة البيانات:
| 1. استخراج هيكل قاعدة البيانات وتحويله إلى JSON
| 2. رفع ملف JSON وعرض الجداول والأعمدة مع إمكانية البحث والنسخ
| 3. تنفيذ استعلامات SQL مباشرة
| 
| يدعم: MySQL, SQLite, PostgreSQL
| 
| الاستخدام:
| - /db-export: لاستخراج هيكل قاعدة البيانات
| - /db-viewer: لعرض وإدارة هياكل قاعدة البيانات
| - /db-executor: لتنفيذ استعلامات SQL
|--------------------------------------------------------------------------
*/

// Route 1: استخراج هيكل قاعدة البيانات
Route::get('/db-export', function () {
    try {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $tables = [];

        // الحصول على أسماء الجداول حسب نوع قاعدة البيانات
        switch ($driver) {
            case 'mysql':
                $tableNames = DB::select('SHOW TABLES');
                $tableKey = 'Tables_in_' . DB::getDatabaseName();
                break;
            case 'sqlite':
                $tableNames = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                $tableKey = 'name';
                break;
            case 'pgsql':
                $tableNames = DB::select("SELECT tablename as name FROM pg_tables WHERE schemaname = 'public'");
                $tableKey = 'name';
                break;
            default:
                throw new Exception("Database driver not supported: $driver");
        }

        // استخراج تفاصيل كل جدول
        foreach ($tableNames as $table) {
            $tableName = $table->$tableKey ?? $table->name;
            $columns = [];

            // الحصول على أعمدة الجدول
            switch ($driver) {
                case 'mysql':
                    $columnInfo = DB::select("DESCRIBE `$tableName`");
                    foreach ($columnInfo as $column) {
                        $columns[] = [
                            'name' => $column->Field,
                            'type' => $column->Type,
                            'null' => $column->Null === 'YES',
                            'key' => $column->Key,
                            'default' => $column->Default,
                            'extra' => $column->Extra
                        ];
                    }
                    break;
                case 'sqlite':
                    $columnInfo = DB::select("PRAGMA table_info(`$tableName`)");
                    foreach ($columnInfo as $column) {
                        $columns[] = [
                            'name' => $column->name,
                            'type' => $column->type,
                            'null' => !$column->notnull,
                            'key' => $column->pk ? 'PRI' : '',
                            'default' => $column->dflt_value,
                            'extra' => ''
                        ];
                    }
                    break;
                case 'pgsql':
                    $columnInfo = DB::select("
                        SELECT column_name as name, data_type as type, is_nullable, column_default as default_value
                        FROM information_schema.columns 
                        WHERE table_name = '$tableName'
                        ORDER BY ordinal_position
                    ");
                    foreach ($columnInfo as $column) {
                        $columns[] = [
                            'name' => $column->name,
                            'type' => $column->type,
                            'null' => $column->is_nullable === 'YES',
                            'key' => '',
                            'default' => $column->default_value,
                            'extra' => ''
                        ];
                    }
                    break;
            }

            $tables[$tableName] = [
                'name' => $tableName,
                'columns' => $columns,
                'row_count' => DB::table($tableName)->count()
            ];
        }

        $schema = [
            'database' => DB::getDatabaseName(),
            'driver' => $driver,
            'exported_at' => now()->toISOString(),
            'tables' => $tables
        ];

        return response()->json($schema, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'فشل في استخراج هيكل قاعدة البيانات',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Route 2: عارض وإدارة هياكل قاعدة البيانات
Route::match(['GET', 'POST'], '/db-viewer', function (Request $request) {
    $uploadedSchema = null;
    $message = '';
    $messageType = '';

    if ($request->isMethod('post')) {
        if ($request->hasFile('schema_file')) {
            try {
                $file = $request->file('schema_file');
                $content = file_get_contents($file->getPathname());
                $schema = json_decode($content, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('ملف JSON غير صالح');
                }

                // حفظ في قاعدة البيانات المحلية
                DB::table('uploaded_schemas')->insert([
                    'filename' => $file->getClientOriginalName(),
                    'schema_data' => $content,
                    'uploaded_at' => now()
                ]);

                $uploadedSchema = $schema;
                $message = 'تم رفع الملف وحفظه بنجاح';
                $messageType = 'success';
            } catch (Exception $e) {
                $message = 'فشل في رفع الملف: ' . $e->getMessage();
                $messageType = 'error';
            }
        } elseif ($request->has('clear_schemas')) {
            try {
                DB::table('uploaded_schemas')->truncate();
                $message = 'تم مسح جميع الملفات المحفوظة';
                $messageType = 'success';
            } catch (Exception $e) {
                $message = 'فشل في مسح الملفات: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    }

    // إنشاء جدول uploaded_schemas إذا لم يكن موجوداً
    if (!Schema::hasTable('uploaded_schemas')) {
        Schema::create('uploaded_schemas', function ($table) {
            $table->id();
            $table->string('filename');
            $table->longText('schema_data');
            $table->timestamp('uploaded_at');
        });
    }

    $savedSchemas = DB::table('uploaded_schemas')->orderBy('uploaded_at', 'desc')->get();

    return response(db_viewer_content($uploadedSchema, $savedSchemas, $message, $messageType));
});

// Route 3: منفذ استعلامات SQL
Route::match(['GET', 'POST'], '/db-executor', function (Request $request) {
    $result = null;
    $error = null;
    $query = $request->input('query', '');

    if ($request->isMethod('post') && !empty($query)) {
        try {
            $connection = DB::connection();

            // تنظيف الاستعلام
            $query = trim($query);

            // تحديد نوع الاستعلام
            $queryType = strtoupper(substr($query, 0, 6));

            if (in_array($queryType, ['SELECT', 'SHOW ', 'DESCRI', 'PRAGMA'])) {
                // استعلامات القراءة
                $result = DB::select($query);
                $result = [
                    'type' => 'select',
                    'data' => $result,
                    'count' => count($result)
                ];
            } else {
                // استعلامات التعديل
                $affected = DB::statement($query);
                $result = [
                    'type' => 'statement',
                    'affected' => $affected,
                    'message' => 'تم تنفيذ الاستعلام بنجاح'
                ];
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    return response(db_executor_content($query, $result, $error));
});

// دالة محتوى عارض قاعدة البيانات
function db_viewer_content($uploadedSchema, $savedSchemas, $message, $messageType)
{
    $geminiKeys = [
        'AIzaSyD7jSzV7S-XwRa8L90KVBxM08g7LSMDeGk',
        'AIzaSyCTYH7rvcxwjemRqYO1_zy6fftpXtJ7x7sA',
        'AIzaSyCwYAwZIqKE_727iTqIbYWLBvrt8ebW-0k',
        'AIzaSyC2uWuYocXExJfqQxeBaV90ZIvdx1EibCc',
        'AIzaSyDa-Ad3iE6JwBMy5mg9me2vfXbrdI3bLQo'
    ];

    return '<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عارض هياكل قاعدة البيانات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
    <style>
        body { font-family: "Cairo", sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .main-container { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin: 20px 0; }
        .header-section { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 30px; border-radius: 15px 15px 0 0; }
        .upload-section { background: #f8f9fa; padding: 25px; border-radius: 10px; margin: 20px 0; }
        .table-card { background: white; border: 1px solid #e9ecef; border-radius: 10px; margin: 15px 0; overflow: hidden; transition: all 0.3s; }
        .table-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); transform: translateY(-2px); }
        .table-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; }
        .column-item { padding: 8px 15px; border-bottom: 1px solid #f1f1f1; transition: background 0.2s; }
        .column-item:hover { background: #f8f9fa; }
        .copy-btn { transition: all 0.2s; }
        .copy-btn:hover { transform: scale(1.05); }
        .search-box { border-radius: 25px; border: 2px solid #e9ecef; padding: 12px 20px; }
        .search-box:focus { border-color: #4facfe; box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25); }
        .ai-section { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); padding: 20px; border-radius: 10px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header-section text-center">
                <h1><i class="fas fa-database me-3"></i>عارض هياكل قاعدة البيانات</h1>
                <p class="mb-0">أداة شاملة لعرض وإدارة هياكل قواعد البيانات مع دعم الذكاء الاصطناعي</p>
            </div>

            <div class="container p-4">
                ' . ($message ? '<div class="alert alert-' . ($messageType === 'success' ? 'success' : 'danger') . ' alert-dismissible fade show">
                    <i class="fas fa-' . ($messageType === 'success' ? 'check-circle' : 'exclamation-triangle') . ' me-2"></i>
                    ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>' : '') . '

                <div class="upload-section">
                    <h3><i class="fas fa-upload me-2"></i>رفع ملف JSON</h3>
                    <form method="POST" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="schema_file" accept=".json" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-upload me-1"></i>رفع
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="clear_schemas" class="btn btn-danger w-100" onclick="return confirm(\'هل أنت متأكد من مسح جميع الملفات؟\')">
                                <i class="fas fa-trash me-1"></i>مسح الكل
                            </button>
                        </div>
                    </form>
                </div>

                ' . (!empty($savedSchemas) ? '
                <div class="mb-4">
                    <h4><i class="fas fa-history me-2"></i>الملفات المحفوظة (' . count($savedSchemas) . ')</h4>
                    <div class="row">
                        ' . collect($savedSchemas)->map(function ($schema) {
        return '<div class="col-md-4 mb-2">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <small class="text-muted">' . $schema->filename . '</small><br>
                                        <small class="text-muted">' . $schema->uploaded_at . '</small>
                                    </div>
                                </div>
                            </div>';
    })->join('') . '
                    </div>
                </div>' : '') . '

                ' . ($uploadedSchema ? '
                <div class="ai-section">
                    <h4><i class="fas fa-robot me-2"></i>تحليل بالذكاء الاصطناعي</h4>
                    <p>استخدم Gemini AI لتحليل هيكل قاعدة البيانات:</p>
                    <div class="row g-2">
                        ' . collect($geminiKeys)->map(function ($key, $index) {
        return '<div class="col-md-2">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="analyzeWithGemini(\'' . $key . '\', ' . ($index + 1) . ')">
                                    مفتاح ' . ($index + 1) . '
                                </button>
                            </div>';
    })->join('') . '
                    </div>
                    <div id="ai-result" class="mt-3" style="display:none;">
                        <div class="card">
                            <div class="card-body">
                                <h6>نتيجة التحليل:</h6>
                                <div id="ai-content"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" class="form-control search-box" placeholder="🔍 البحث في الجداول والأعمدة...">
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group w-100">
                            <button class="btn btn-success" onclick="copyAllTables()">
                                <i class="fas fa-copy me-1"></i>نسخ جميع الجداول
                            </button>
                            <button class="btn btn-info" onclick="copySelectedTables()">
                                <i class="fas fa-check-square me-1"></i>نسخ المحدد
                            </button>
                            <button class="btn btn-warning" onclick="selectAllTables()">
                                <i class="fas fa-select-all me-1"></i>تحديد الكل
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h4><i class="fas fa-info-circle me-2"></i>معلومات قاعدة البيانات</h4>
                        <div class="card">
                            <div class="card-body">
                                <p><strong>اسم قاعدة البيانات:</strong> ' . ($uploadedSchema['database'] ?? 'غير محدد') . '</p>
                                <p><strong>نوع قاعدة البيانات:</strong> ' . ($uploadedSchema['driver'] ?? 'غير محدد') . '</p>
                                <p><strong>تاريخ الاستخراج:</strong> ' . ($uploadedSchema['exported_at'] ?? 'غير محدد') . '</p>
                                <p><strong>عدد الجداول:</strong> ' . count($uploadedSchema['tables'] ?? []) . '</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4><i class="fas fa-clipboard me-2"></i>منطقة النسخ</h4>
                        <textarea id="copyArea" class="form-control" rows="8" placeholder="ستظهر هنا الجداول والأعمدة المنسوخة..." readonly></textarea>
                    </div>
                </div>

                <div id="tablesContainer">
                    ' . collect($uploadedSchema['tables'] ?? [])->map(function ($table, $tableName) {
        return '<div class="table-card" data-table="' . $tableName . '">
                            <div class="table-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="mb-0">
                                            <input type="checkbox" class="form-check-input me-2 table-checkbox" data-table="' . $tableName . '">
                                            <i class="fas fa-table me-2"></i>' . $tableName . ' 
                                            <span class="badge bg-light text-dark ms-2">' . count($table['columns']) . ' عمود</span>
                                            <span class="badge bg-warning text-dark ms-1">' . ($table['row_count'] ?? 0) . ' صف</span>
                                        </h5>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-light btn-sm copy-btn" onclick="copyTable(\'' . $tableName . '\')">
                                            <i class="fas fa-copy me-1"></i>نسخ الجدول
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-body">
                                ' . collect($table['columns'])->map(function ($column) {
            $typeColor = match (true) {
                str_contains(strtolower($column['type']), 'int') => 'primary',
                str_contains(strtolower($column['type']), 'varchar') || str_contains(strtolower($column['type']), 'text') => 'success',
                str_contains(strtolower($column['type']), 'date') || str_contains(strtolower($column['type']), 'time') => 'warning',
                default => 'secondary'
            };

            return '<div class="column-item" data-column="' . $column['name'] . '">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <strong>' . $column['name'] . '</strong>
                                                ' . ($column['key'] === 'PRI' ? '<i class="fas fa-key text-warning ms-1" title="Primary Key"></i>' : '') . '
                                            </div>
                                            <div class="col-md-3">
                                                <span class="badge bg-' . $typeColor . '">' . $column['type'] . '</span>
                                            </div>
                                            <div class="col-md-2">
                                                ' . ($column['null'] ? '<span class="badge bg-success">NULL</span>' : '<span class="badge bg-danger">NOT NULL</span>') . '
                                            </div>
                                            <div class="col-md-2">
                                                ' . ($column['default'] ? '<small class="text-muted">افتراضي: ' . $column['default'] . '</small>' : '') . '
                                            </div>
                                            <div class="col-md-2">
                                                ' . ($column['extra'] ? '<small class="text-info">' . $column['extra'] . '</small>' : '') . '
                                            </div>
                                        </div>
                                    </div>';
        })->join('') . '
                            </div>
                        </div>';
    })->join('') . '
                </div>' : '
                <div class="text-center py-5">
                    <i class="fas fa-upload fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لم يتم رفع أي ملف بعد</h4>
                    <p class="text-muted">قم برفع ملف JSON لعرض هيكل قاعدة البيانات</p>
                </div>') . '
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // البحث في الجداول والأعمدة
        document.getElementById("searchInput")?.addEventListener("input", function() {
            const searchTerm = this.value.toLowerCase();
            const tables = document.querySelectorAll(".table-card");
            
            tables.forEach(table => {
                const tableName = table.dataset.table.toLowerCase();
                const columns = table.querySelectorAll(".column-item");
                let hasMatch = tableName.includes(searchTerm);
                
                columns.forEach(column => {
                    const columnName = column.dataset.column.toLowerCase();
                    const columnMatch = columnName.includes(searchTerm);
                    column.style.display = columnMatch || searchTerm === "" ? "block" : "none";
                    if (columnMatch) hasMatch = true;
                });
                
                table.style.display = hasMatch || searchTerm === "" ? "block" : "none";
            });
        });

        // نسخ جدول واحد
        function copyTable(tableName) {
            const schemaData = ' . json_encode($uploadedSchema) . ';
            if (!schemaData || !schemaData.tables || !schemaData.tables[tableName]) return;
            
            const table = schemaData.tables[tableName];
            let output = `-- جدول: ${tableName}\n`;
            output += `-- عدد الأعمدة: ${table.columns.length}\n`;
            output += `-- عدد الصفوف: ${table.row_count || 0}\n\n`;
            
            table.columns.forEach(column => {
                output += `${column.name} | ${column.type} | ${column.null ? "NULL" : "NOT NULL"}`;
                if (column.key === "PRI") output += " | PRIMARY KEY";
                if (column.default) output += ` | DEFAULT: ${column.default}`;
                if (column.extra) output += ` | ${column.extra}`;
                output += "\n";
            });
            output += "\n" + "=".repeat(50) + "\n\n";
            
            document.getElementById("copyArea").value = output;
            navigator.clipboard.writeText(output);
            
            Toastify({
                text: `تم نسخ جدول ${tableName}`,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                duration: 3000
            }).showToast();
        }

        // نسخ جميع الجداول
        function copyAllTables() {
            const schemaData = ' . json_encode($uploadedSchema) . ';
            if (!schemaData || !schemaData.tables) return;
            
            let output = `-- قاعدة البيانات: ${schemaData.database}\n`;
            output += `-- النوع: ${schemaData.driver}\n`;
            output += `-- تاريخ الاستخراج: ${schemaData.exported_at}\n`;
            output += `-- عدد الجداول: ${Object.keys(schemaData.tables).length}\n\n`;
            output += "=".repeat(80) + "\n\n";
            
            Object.entries(schemaData.tables).forEach(([tableName, table]) => {
                output += `-- جدول: ${tableName}\n`;
                output += `-- عدد الأعمدة: ${table.columns.length}\n`;
                output += `-- عدد الصفوف: ${table.row_count || 0}\n\n`;
                
                table.columns.forEach(column => {
                    output += `${column.name} | ${column.type} | ${column.null ? "NULL" : "NOT NULL"}`;
                    if (column.key === "PRI") output += " | PRIMARY KEY";
                    if (column.default) output += ` | DEFAULT: ${column.default}`;
                    if (column.extra) output += ` | ${column.extra}`;
                    output += "\n";
                });
                output += "\n" + "=".repeat(50) + "\n\n";
            });
            
            document.getElementById("copyArea").value = output;
            navigator.clipboard.writeText(output);
            
            Toastify({
                text: "تم نسخ جميع الجداول",
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                duration: 3000
            }).showToast();
        }

        // نسخ الجداول المحددة
        function copySelectedTables() {
            const selectedCheckboxes = document.querySelectorAll(".table-checkbox:checked");
            if (selectedCheckboxes.length === 0) {
                Swal.fire("تنبيه", "لم يتم تحديد أي جداول", "warning");
                return;
            }
            
            const schemaData = ' . json_encode($uploadedSchema) . ';
            let output = `-- الجداول المحددة (${selectedCheckboxes.length})\n\n`;
            
            selectedCheckboxes.forEach(checkbox => {
                const tableName = checkbox.dataset.table;
                const table = schemaData.tables[tableName];
                
                output += `-- جدول: ${tableName}\n`;
                output += `-- عدد الأعمدة: ${table.columns.length}\n`;
                output += `-- عدد الصفوف: ${table.row_count || 0}\n\n`;
                
                table.columns.forEach(column => {
                    output += `${column.name} | ${column.type} | ${column.null ? "NULL" : "NOT NULL"}`;
                    if (column.key === "PRI") output += " | PRIMARY KEY";
                    if (column.default) output += ` | DEFAULT: ${column.default}`;
                    if (column.extra) output += ` | ${column.extra}`;
                    output += "\n";
                });
                output += "\n" + "=".repeat(50) + "\n\n";
            });
            
            document.getElementById("copyArea").value = output;
            navigator.clipboard.writeText(output);
            
            Toastify({
                text: `تم نسخ ${selectedCheckboxes.length} جدول`,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                duration: 3000
            }).showToast();
        }

        // تحديد جميع الجداول
        function selectAllTables() {
            const checkboxes = document.querySelectorAll(".table-checkbox");
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            
            Toastify({
                text: allChecked ? "تم إلغاء تحديد جميع الجداول" : "تم تحديد جميع الجداول",
                backgroundColor: "linear-gradient(to right, #667eea, #764ba2)",
                duration: 2000
            }).showToast();
        }

        // تحليل بالذكاء الاصطناعي
        async function analyzeWithGemini(apiKey, keyNumber) {
            const schemaData = ' . json_encode($uploadedSchema) . ';
            if (!schemaData) return;
            
            const prompt = `قم بتحليل هيكل قاعدة البيانات التالية وقدم ملاحظات وتوصيات:
            
قاعدة البيانات: ${schemaData.database}
النوع: ${schemaData.driver}
عدد الجداول: ${Object.keys(schemaData.tables).length}

الجداول:
${Object.entries(schemaData.tables).map(([name, table]) => 
    `- ${name}: ${table.columns.length} عمود، ${table.row_count || 0} صف`
).join("\n")}

يرجى تقديم:
1. تحليل عام لهيكل قاعدة البيانات
2. ملاحظات على التصميم
3. توصيات للتحسين
4. مشاكل محتملة`;

            try {
                document.getElementById("ai-result").style.display = "block";
                document.getElementById("ai-content").innerHTML = `<div class="text-center"><i class="fas fa-spinner fa-spin"></i> جاري التحليل باستخدام المفتاح ${keyNumber}...</div>`;
                
                const response = await fetch("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-goog-api-key": apiKey
                    },
                    body: JSON.stringify({
                        contents: [{
                            parts: [{
                                text: prompt
                            }]
                        }]
                    })
                });
                
                const data = await response.json();
                
                if (data.candidates && data.candidates[0] && data.candidates[0].content) {
                    const analysis = data.candidates[0].content.parts[0].text;
                    document.getElementById("ai-content").innerHTML = `<pre style="white-space: pre-wrap; font-family: Cairo, sans-serif;">${analysis}</pre>`;
                    
                    Toastify({
                        text: `تم التحليل بنجاح باستخدام المفتاح ${keyNumber}`,
                        backgroundColor: "linear-gradient(to right, #ff9a9e, #fecfef)",
                        duration: 3000
                    }).showToast();
                } else {
                    throw new Error("لم يتم الحصول على رد صالح من الذكاء الاصطناعي");
                }
                
            } catch (error) {
                document.getElementById("ai-content").innerHTML = `<div class="alert alert-danger">خطأ في التحليل باستخدام المفتاح ${keyNumber}: ${error.message}</div>`;
                
                Toastify({
                    text: `فشل التحليل بالمفتاح ${keyNumber}`,
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    duration: 3000
                }).showToast();
            }
        }
    </script>
</body>
</html>';
}

// دالة محتوى منفذ الاستعلامات
function db_executor_content($query, $result, $error)
{
    return '<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منفذ استعلامات SQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
    <style>
        body { font-family: "Cairo", sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .main-container { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin: 20px 0; }
        .header-section { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); color: white; padding: 30px; border-radius: 15px 15px 0 0; }
        .query-section { background: #f8f9fa; padding: 25px; border-radius: 10px; margin: 20px 0; }
        .result-section { background: white; border: 1px solid #e9ecef; border-radius: 10px; margin: 20px 0; overflow: hidden; }
        .query-textarea { font-family: "Courier New", monospace; border-radius: 10px; border: 2px solid #e9ecef; }
        .query-textarea:focus { border-color: #ff6b6b; box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25); }
        .result-table { font-size: 0.9rem; }
        .result-table th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .quick-queries { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); padding: 20px; border-radius: 10px; margin: 20px 0; }
        .quick-query-btn { margin: 5px; transition: all 0.2s; }
        .quick-query-btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header-section text-center">
                <h1><i class="fas fa-terminal me-3"></i>منفذ استعلامات SQL</h1>
                <p class="mb-0">تنفيذ استعلامات SQL مباشرة على قاعدة البيانات - يدعم MySQL, SQLite, PostgreSQL</p>
            </div>

            <div class="container p-4">
                <div class="quick-queries">
                    <h4><i class="fas fa-bolt me-2"></i>استعلامات سريعة</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary quick-query-btn w-100" onclick="setQuery(\'SHOW TABLES;\')">
                                <i class="fas fa-list me-1"></i>عرض الجداول
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-success quick-query-btn w-100" onclick="setQuery(\'SELECT DATABASE();\')">
                                <i class="fas fa-database me-1"></i>اسم قاعدة البيانات
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-info quick-query-btn w-100" onclick="setQuery(\'SELECT VERSION();\')">
                                <i class="fas fa-info-circle me-1"></i>إصدار قاعدة البيانات
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-warning quick-query-btn w-100" onclick="setQuery(\'SELECT NOW();\')">
                                <i class="fas fa-clock me-1"></i>الوقت الحالي
                            </button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <button class="btn btn-outline-secondary quick-query-btn w-100" onclick="setQuery(\'CREATE TABLE example (\\n    id INT PRIMARY KEY AUTO_INCREMENT,\\n    name VARCHAR(255) NOT NULL,\\n    email VARCHAR(255) UNIQUE,\\n    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\\n);\')">
                                <i class="fas fa-plus me-1"></i>إنشاء جدول مثال
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-dark quick-query-btn w-100" onclick="setQuery(\'SELECT * FROM information_schema.tables WHERE table_schema = DATABASE();\')">
                                <i class="fas fa-search me-1"></i>معلومات الجداول
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-danger quick-query-btn w-100" onclick="clearQuery()">
                                <i class="fas fa-eraser me-1"></i>مسح الاستعلام
                            </button>
                        </div>
                    </div>
                </div>

                <form method="POST" class="query-section">
                    <h3><i class="fas fa-code me-2"></i>كتابة الاستعلام</h3>
                    <div class="mb-3">
                        <textarea name="query" id="queryTextarea" class="form-control query-textarea" rows="8" placeholder="اكتب استعلام SQL هنا...

أمثلة:
-- عرض البيانات
SELECT * FROM users LIMIT 10;

-- إنشاء جدول
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2)
);

-- إدراج بيانات
INSERT INTO users (name, email) VALUES (\'أحمد محمد\', \'ahmed@example.com\');

-- تحديث البيانات
UPDATE users SET name = \'محمد أحمد\' WHERE id = 1;

-- حذف البيانات
DELETE FROM users WHERE id = 1;">' . htmlspecialchars($query) . '</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-play me-2"></i>تنفيذ الاستعلام
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-secondary btn-lg w-100" onclick="copyQuery()">
                                <i class="fas fa-copy me-2"></i>نسخ الاستعلام
                            </button>
                        </div>
                        <div class="col-md-3"></div>                        <button type="button" class="btn btn-info btn-lg w-100" onclick="formatQuery()">
                                <i class="fas fa-magic me-2"></i>تنسيق الاستعلام
                            </button>
                        </div>
                    </div>
                </form>

                ' . ($error ? '
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>خطأ في تنفيذ الاستعلام</h5>
                    <pre style="white-space: pre-wrap; font-family: Courier New, monospace;">' . htmlspecialchars($error) . '</pre>
                </div>' : '') . '

                ' . ($result ? '
                <div class="result-section">
                    <div class="p-3 bg-light border-bottom">
                        <h4><i class="fas fa-check-circle text-success me-2"></i>نتيجة التنفيذ</h4>
                    </div>
                    <div class="p-3">
                        ' . ($result['type'] === 'select' ? '
                        <div class="mb-3">
                            <span class="badge bg-success">تم العثور على ' . $result['count'] . ' نتيجة</span>
                            <button class="btn btn-sm btn-outline-primary ms-2" onclick="exportResults()">
                                <i class="fas fa-download me-1"></i>تصدير النتائج
                            </button>
                        </div>
                        ' . (count($result['data']) > 0 ? '
                        <div class="table-responsive">
                            <table class="table table-striped table-hover result-table">
                                <thead>
                                    <tr>
                                        ' . collect(array_keys((array)$result['data'][0]))->map(function ($column) {
        return '<th>' . htmlspecialchars($column) . '</th>';
    })->join('') . '
                                    </tr>
                                </thead>
                                <tbody>
                                    ' . collect($result['data'])->map(function ($row) {
        return '<tr>' . collect((array)$row)->map(function ($value) {
            return '<td>' . htmlspecialchars($value ?? 'NULL') . '</td>';
        })->join('') . '</tr>';
    })->join('') . '
                                </tbody>
                            </table>
                        </div>' : '
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>لا توجد نتائج للعرض
                        </div>') : '
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check me-2"></i>' . $result['message'] . '</h5>
                            ' . (isset($result['affected']) ? '<p>تم التأثير على العملية بنجاح</p>' : '') . '
                        </div>') . '
                    </div>
                </div>' : '') . '

                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-download fa-2x text-primary mb-2"></i>
                                    <h6>استخراج هيكل قاعدة البيانات</h6>
                                    <a href="/db-export" class="btn btn-primary btn-sm">
                                        <i class="fas fa-external-link-alt me-1"></i>انتقال
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-eye fa-2x text-success mb-2"></i>
                                    <h6>عارض هياكل قاعدة البيانات</h6>
                                    <a href="/db-viewer" class="btn btn-success btn-sm">
                                        <i class="fas fa-external-link-alt me-1"></i>انتقال
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-refresh fa-2x text-warning mb-2"></i>
                                    <h6>تحديث الصفحة</h6>
                                    <button class="btn btn-warning btn-sm" onclick="location.reload()">
                                        <i class="fas fa-refresh me-1"></i>تحديث
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const resultData = ' . json_encode($result) . ';
        
        function setQuery(query) {
            document.getElementById("queryTextarea").value = query;
            
            Toastify({
                text: "تم تعيين الاستعلام",
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                duration: 2000
            }).showToast();
        }

        function clearQuery() {
            document.getElementById("queryTextarea").value = "";
            
            Toastify({
                text: "تم مسح الاستعلام",
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                duration: 2000
            }).showToast();
        }

        function copyQuery() {
            const textarea = document.getElementById("queryTextarea");
            textarea.select();
            document.execCommand("copy");
            
            Toastify({
                text: "تم نسخ الاستعلام",
                backgroundColor: "linear-gradient(to right, #667eea, #764ba2)",
                duration: 2000
            }).showToast();
        }

        function formatQuery() {
            const textarea = document.getElementById("queryTextarea");
            let query = textarea.value;
            
            // تنسيق بسيط للاستعلام
            query = query.replace(/\s+/g, " ");
            query = query.replace(/,/g, ",\n    ");
            query = query.replace(/FROM/gi, "\nFROM");
            query = query.replace(/WHERE/gi, "\nWHERE");
            query = query.replace(/ORDER BY/gi, "\nORDER BY");
            query = query.replace(/GROUP BY/gi, "\nGROUP BY");
            query = query.replace(/HAVING/gi, "\nHAVING");
            query = query.replace(/LIMIT/gi, "\nLIMIT");
            
            textarea.value = query;
            
            Toastify({
                text: "تم تنسيق الاستعلام",
                backgroundColor: "linear-gradient(to right, #ffecd2, #fcb69f)",
                duration: 2000
            }).showToast();
        }

        function exportResults() {
            if (!resultData || resultData.type !== "select" || !resultData.data) {
                Swal.fire("تنبيه", "لا توجد نتائج للتصدير", "warning");
                return;
            }
            
            let csv = "";
            
            // إضافة العناوين
            if (resultData.data.length > 0) {
                const headers = Object.keys(resultData.data[0]);
                csv += headers.join(",") + "\n";
                
                // إضافة البيانات
                resultData.data.forEach(row => {
                    const values = headers.map(header => {
                        const value = row[header];
                        return value !== null && value !== undefined ? `"${value}"` : "";
                    });
                    csv += values.join(",") + "\n";
                });
            }
            
            // تحميل الملف
            const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", "query_results.csv");
            link.style.visibility = "hidden";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            Toastify({
                text: "تم تصدير النتائج",
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                duration: 3000
            }).showToast();
        }

        // اختصارات لوحة المفاتيح
        document.getElementById("queryTextarea").addEventListener("keydown", function(e) {
            if (e.ctrlKey && e.key === "Enter") {
                e.preventDefault();
                document.querySelector("form").submit();
            }
        });
    </script>
</body>
</html>';
}
