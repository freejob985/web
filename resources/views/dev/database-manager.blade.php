<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مدير قاعدة البيانات - Database Manager</title>
    
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Material Design Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Google Fonts - Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Toast.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin: 20px auto;
            padding: 30px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Cairo', sans-serif;
        }
        
        .header p {
            color: #7f8c8d;
            font-size: 1.1rem;
            font-family: 'Tajawal', sans-serif;
            font-weight: 400;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-family: 'Tajawal', sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .search-box {
            position: relative;
            margin-bottom: 20px;
        }
        
        .search-box .form-control {
            padding-right: 45px;
        }
        
        .search-box .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .spinner-border {
            color: #667eea;
        }
        
        .result-section {
            display: none;
            margin-top: 20px;
        }
        
        .copy-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .copy-btn:hover {
            background: #218838;
            transform: scale(1.05);
        }
        
        .tabs {
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 30px;
        }
        
        .tab-btn {
            background: none;
            border: none;
            padding: 15px 25px;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .query-textarea {
            min-height: 200px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .file-upload-area {
            border: 2px dashed #667eea;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            background: #f8f9ff;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .file-upload-area:hover {
            border-color: #5a6fd8;
            background: #f0f2ff;
        }
        
        .file-upload-area.dragover {
            border-color: #28a745;
            background: #d4edda;
        }
        
        .schema-item {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .schema-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-color: #667eea;
        }
        
        .schema-item.selected {
            border-color: #28a745;
            background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
        }
        
        .schema-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .schema-item.selected::before {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .table-structure {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .column-item {
            background: white;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 8px;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .column-name {
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
            color: #2c3e50;
        }
        
        .column-type {
            color: #6c757d;
            font-size: 0.9rem;
            font-family: 'Tajawal', sans-serif;
        }
        
        .column-constraints {
            font-size: 0.8rem;
            color: #28a745;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Cairo', sans-serif;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 0.9rem;
            font-family: 'Tajawal', sans-serif;
            opacity: 0.9;
        }
        
        /* SweetAlert custom styles */
        .swal-wide {
            width: 600px !important;
        }
        
        .swal-wide textarea {
            font-family: 'Courier New', monospace !important;
            direction: ltr !important;
            text-align: left !important;
        }
        
        /* Grid Layout for Tables */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
            will-change: transform;
        }
        
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-color: #667eea;
        }
        
        .table-card.selected {
            border-color: #28a745;
            background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
        }
        
        .table-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .table-card.selected::before {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .table-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .table-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            font-family: 'Cairo', sans-serif;
            margin: 0;
        }
        
        .table-card-actions {
            display: flex;
            gap: 8px;
        }
        
        .table-card-info {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 15px;
            font-family: 'Tajawal', sans-serif;
        }
        
        .table-card-columns {
            max-height: 200px;
            overflow-y: auto;
            margin-top: 15px;
        }
        
        .column-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 6px;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
        }
        
        .column-name {
            font-weight: 600;
            color: #2c3e50;
            font-family: 'Cairo', sans-serif;
        }
        
        .column-type {
            color: #6c757d;
            font-family: 'Tajawal', sans-serif;
        }
        
        .column-constraints {
            font-size: 0.7rem;
            color: #28a745;
        }
        
        /* Alphabet Filter */
        .alphabet-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
            justify-content: center;
        }
        
        .alphabet-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 8px 16px;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
            min-width: 40px;
            text-align: center;
        }
        
        .alphabet-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .alphabet-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .alphabet-btn.all {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }
        
        .alphabet-btn.all.active {
            background: #20c997;
            border-color: #20c997;
        }
        
        /* Selection Controls */
        .selection-controls {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
        }
        
        .selection-controls.show {
            display: block;
        }
        
        .selection-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .selection-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        /* Checkbox Styling */
        .table-checkbox {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #28a745;
        }
        
        .table-card.has-checkbox {
            padding-top: 40px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .empty-state h4 {
            font-family: 'Cairo', sans-serif;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            font-family: 'Tajawal', sans-serif;
        }
        
        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
            gap: 10px;
        }
        
        .pagination-info {
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 25px;
            font-family: 'Tajawal', sans-serif;
            color: #6c757d;
            font-weight: 500;
        }
        
        .pagination-btn {
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
        }
        
        .pagination-btn:hover:not(:disabled) {
            background: #5a6fd8;
            transform: scale(1.1);
        }
        
        .pagination-btn:disabled {
            background: #dee2e6;
            color: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        
        .pagination-btn.active {
            background: #28a745;
        }
        
        .pagination-numbers {
            display: flex;
            gap: 5px;
        }
        
        .pagination-number {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #e9ecef;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
        }
        
        .pagination-number:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .pagination-number.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        /* Performance Optimizations */
        .table-card, .schema-item {
            will-change: transform;
            transform: translateZ(0);
        }
        
        .table-card:hover, .schema-item:hover {
            will-change: transform;
        }
        
        /* Loading States */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 15px;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <!-- Header -->
            <div class="header">
                <h1><i class="fas fa-database"></i> مدير قاعدة البيانات</h1>
                <p>أداة شاملة لإدارة وتحليل هياكل قواعد البيانات</p>
            </div>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn active" onclick="showTab('schema-tab')">
                    <i class="fas fa-table"></i> هيكل قاعدة البيانات
                </button>
                <button class="tab-btn" onclick="showTab('upload-tab')">
                    <i class="fas fa-upload"></i> رفع ملف JSON
                </button>
                <button class="tab-btn" onclick="showTab('query-tab')">
                    <i class="fas fa-code"></i> تنفيذ الاستعلامات
                </button>
                <button class="tab-btn" onclick="showTab('saved-tab')">
                    <i class="fas fa-save"></i> الملفات المحفوظة
                </button>
            </div>
            
            <!-- Schema Tab -->
            <div id="schema-tab" class="tab-content active">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-database"></i> هيكل قاعدة البيانات الحالية
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <button class="btn btn-primary" onclick="loadDatabaseSchema()">
                                    <i class="fas fa-sync"></i> تحديث الهيكل
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success" onclick="downloadSchema()">
                                    <i class="fas fa-download"></i> تحميل كـ JSON
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-warning" onclick="toggleSelectionMode()">
                                    <i class="fas fa-check-square"></i> وضع الاختيار
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-info" onclick="toggleViewMode()">
                                    <i class="fas fa-th"></i> عرض الشبكة
                                </button>
                            </div>
                        </div>
                        
                        <div class="loading" id="schema-loading">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <p>جاري جلب هيكل قاعدة البيانات...</p>
                        </div>
                        
                        <div id="schema-results" class="result-section">
                            <!-- Selection Controls -->
                            <div id="selection-controls" class="selection-controls">
                                <div class="selection-info">
                                    <span id="selection-count">0 جدول محدد</span>
                                    <div class="selection-actions">
                                        <button class="btn btn-sm btn-light" onclick="selectAllTables()">
                                            <i class="fas fa-check-double"></i> تحديد الكل
                                        </button>
                                        <button class="btn btn-sm btn-light" onclick="deselectAllTables()">
                                            <i class="fas fa-times"></i> إلغاء الكل
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="copySelectedTables()">
                                            <i class="fas fa-copy"></i> نسخ المحدد
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Alphabet Filter -->
                            <div class="alphabet-filter" id="alphabet-filter">
                                <button class="alphabet-btn all active" onclick="filterByAlphabet('all')">All</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('a')">A</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('b')">B</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('c')">C</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('d')">D</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('e')">E</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('f')">F</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('g')">G</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('h')">H</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('i')">I</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('j')">J</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('k')">K</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('l')">L</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('m')">M</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('n')">N</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('o')">O</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('p')">P</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('q')">Q</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('r')">R</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('s')">S</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('t')">T</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('u')">U</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('v')">V</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('w')">W</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('x')">X</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('y')">Y</button>
                                <button class="alphabet-btn" onclick="filterByAlphabet('z')">Z</button>
                            </div>
                            
                            <div class="search-box">
                                <input type="text" class="form-control" id="schema-search" placeholder="البحث في الجداول والأعمدة...">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                            
                            <div id="schema-content"></div>
                            
                            <!-- Pagination -->
                            <div id="pagination-container" class="pagination-container" style="display: none;">
                                <button class="pagination-btn" id="prev-btn" onclick="changePage(-1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                
                                <div class="pagination-numbers" id="pagination-numbers">
                                    <!-- Page numbers will be generated here -->
                                </div>
                                
                                <button class="pagination-btn" id="next-btn" onclick="changePage(1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                
                                <div class="pagination-info" id="pagination-info">
                                    <!-- Page info will be generated here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upload Tab -->
            <div id="upload-tab" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-upload"></i> رفع ملف JSON
                    </div>
                    <div class="card-body">
                        <div class="file-upload-area" id="file-upload-area">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5>اسحب ملف JSON هنا أو انقر للاختيار</h5>
                            <p class="text-muted">الحد الأقصى: 10 ميجابايت</p>
                            <input type="file" id="schema-file" accept=".json" style="display: none;">
                        </div>
                        
                        <div id="upload-results" class="result-section">
                            <div class="search-box">
                                <input type="text" class="form-control" id="upload-search" placeholder="البحث في الجداول والأعمدة...">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                            
                            <div id="upload-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Query Tab -->
            <div id="query-tab" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-code"></i> تنفيذ الاستعلامات SQL
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">اكتب استعلام SQL:</label>
                            <textarea class="form-control query-textarea" id="sql-query" placeholder="مثال: SELECT * FROM users LIMIT 10;"></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <button class="btn btn-primary" onclick="executeQuery()">
                                    <i class="fas fa-play"></i> تنفيذ الاستعلام
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-warning" onclick="clearQuery()">
                                    <i class="fas fa-trash"></i> مسح الاستعلام
                                </button>
                            </div>
                        </div>
                        
                        <div class="loading" id="query-loading">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">جاري التنفيذ...</span>
                            </div>
                            <p>جاري تنفيذ الاستعلام...</p>
                        </div>
                        
                        <div id="query-results" class="result-section">
                            <div id="query-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Saved Tab -->
            <div id="saved-tab" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-save"></i> الملفات المحفوظة
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-3" onclick="loadSavedSchemas()">
                            <i class="fas fa-refresh"></i> تحديث القائمة
                        </button>
                        
                        <div id="saved-schemas"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Global variables
        let currentSchema = null;
        let currentUploadedSchema = null;
        let isSelectionMode = false;
        let isGridView = true;
        let selectedTables = new Set();
        let currentAlphabetFilter = 'all';
        let currentPage = 1;
        let itemsPerPage = 10;
        let filteredTables = [];
        let displayCache = new Map();
        
        // Get CSRF token
        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
        
        // Toast configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-left",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "rtl": true
        };
        
        // Apply Arabic fonts to dynamically created elements
        function applyArabicFonts() {
            // Apply Cairo font to headings and buttons
            document.querySelectorAll('h1, h2, h3, h4, h5, h6, .btn, .tab-btn').forEach(el => {
                el.style.fontFamily = "'Cairo', sans-serif";
            });
            
            // Apply Tajawal font to text elements
            document.querySelectorAll('p, span, div, .form-control, .form-select, .table td').forEach(el => {
                if (!el.style.fontFamily) {
                    el.style.fontFamily = "'Tajawal', sans-serif";
                }
            });
        }
        
        // Tab switching
        function showTab(tabId) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        // Load database schema
        function loadDatabaseSchema() {
            const loading = document.getElementById('schema-loading');
            const results = document.getElementById('schema-results');
            
            loading.style.display = 'block';
            results.style.display = 'none';
            
            fetch('/dev/database-schema', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    results.style.display = 'block';
                    
                    if (data.success) {
                        currentSchema = data;
                        displaySchema(data, 'schema-content');
                        toastr.success(`تم جلب ${data.count} جدول بنجاح`);
                    } else {
                        toastr.error('خطأ في جلب هيكل قاعدة البيانات: ' + data.error);
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    toastr.error('خطأ في الاتصال: ' + error.message);
                });
        }
        
        // Display schema with pagination
        function displaySchema(schema, containerId) {
            const container = document.getElementById(containerId);
            let html = '';
            
            if (schema.tables && schema.tables.length > 0) {
                // Update filtered tables
                filteredTables = schema.tables;
                
                // Stats
                html += `
                    <div class="stats-card">
                        <div class="stats-number">${schema.tables.length}</div>
                        <div class="stats-label">جدول في قاعدة البيانات</div>
                    </div>
                `;
                
                // Calculate pagination
                const totalPages = Math.ceil(filteredTables.length / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, filteredTables.length);
                const currentTables = filteredTables.slice(startIndex, endIndex);
                
                // Tables
                if (isGridView) {
                    html += '<div class="tables-grid">';
                    currentTables.forEach(table => {
                        html += createTableCard(table);
                    });
                    html += '</div>';
                } else {
                    currentTables.forEach(table => {
                        html += createTableItem(table);
                    });
                }
                
                // Pagination
                if (totalPages > 1) {
                    html += generatePagination(totalPages, currentPage, startIndex + 1, endIndex, filteredTables.length);
                }
            } else {
                html = `
                    <div class="empty-state">
                        <i class="fas fa-database"></i>
                        <h4>لا توجد جداول</h4>
                        <p>لا توجد جداول في قاعدة البيانات الحالية</p>
                    </div>
                `;
            }
            
            container.innerHTML = html;
            
            // Apply Arabic fonts to newly created elements
            requestAnimationFrame(() => {
                applyArabicFonts();
            });
        }
        
        // Generate pagination HTML
        function generatePagination(totalPages, currentPage, startItem, endItem, totalItems) {
            let html = `
                <div class="pagination-container">
                    <button class="pagination-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(-1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="pagination-numbers">
            `;
            
            // Generate page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                html += `<button class="pagination-number" onclick="goToPage(1)">1</button>`;
                if (startPage > 2) {
                    html += `<span class="pagination-number">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `<button class="pagination-number ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<span class="pagination-number">...</span>`;
                }
                html += `<button class="pagination-number" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }
            
            html += `
                    </div>
                    
                    <button class="pagination-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="pagination-info">
                        عرض ${startItem}-${endItem} من ${totalItems} جدول
                    </div>
                </div>
            `;
            
            return html;
        }
        
        // Create table card for grid view
        function createTableCard(table) {
            const isSelected = selectedTables.has(table.name);
            const checkboxHtml = isSelectionMode ? 
                `<input type="checkbox" class="table-checkbox" ${isSelected ? 'checked' : ''} onchange="toggleTableSelection('${table.name}')">` : '';
            
            return `
                <div class="table-card ${isSelected ? 'selected' : ''} ${isSelectionMode ? 'has-checkbox' : ''}" data-table="${table.name}">
                    ${checkboxHtml}
                    <div class="table-card-header">
                        <h5 class="table-card-title">
                            <i class="fas fa-table text-primary"></i> ${table.name}
                        </h5>
                        <div class="table-card-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="copyTable('${table.name}')" title="نسخ الجدول">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="toggleTable('${table.name}')" title="عرض الأعمدة">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-card-info">
                        <i class="fas fa-columns"></i> ${table.columns.length} عمود
                    </div>
                    
                    <div class="table-card-columns" id="table-${table.name}" style="display: none;">
                        ${table.columns.map(column => `
                            <div class="column-item">
                                <div>
                                    <div class="column-name">${column.name}</div>
                                    <div class="column-type">${column.type}</div>
                                </div>
                                <div class="column-constraints">
                                    ${column.pk ? '<span class="badge bg-primary me-1">PK</span>' : ''}
                                    ${column.notnull || column.nullable === false ? '<span class="badge bg-warning me-1">NN</span>' : ''}
                                    ${column.default ? '<span class="badge bg-info me-1">DEF</span>' : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        // Create table item for list view
        function createTableItem(table) {
            const isSelected = selectedTables.has(table.name);
            const checkboxHtml = isSelectionMode ? 
                `<input type="checkbox" class="form-check-input me-2" ${isSelected ? 'checked' : ''} onchange="toggleTableSelection('${table.name}')">` : '';
            
            return `
                <div class="schema-item ${isSelected ? 'selected' : ''}" data-table="${table.name}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            ${checkboxHtml}
                            <i class="fas fa-table text-primary"></i> ${table.name}
                        </h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2" onclick="copyTable('${table.name}')">
                                <i class="fas fa-copy"></i> نسخ الجدول
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="toggleTable('${table.name}')">
                                <i class="fas fa-eye"></i> عرض الأعمدة
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-structure" id="table-${table.name}" style="display: none;">
                        <h6 class="mb-3">الأعمدة (${table.columns.length}):</h6>
                        ${table.columns.map(column => `
                            <div class="column-item">
                                <div>
                                    <div class="column-name">${column.name}</div>
                                    <div class="column-type">${column.type}</div>
                                </div>
                                <div class="column-constraints">
                                    ${column.pk ? '<span class="badge bg-primary me-1">Primary Key</span>' : ''}
                                    ${column.notnull || column.nullable === false ? '<span class="badge bg-warning me-1">Not Null</span>' : ''}
                                    ${column.default ? '<span class="badge bg-info me-1">Default: ' + column.default + '</span>' : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        // Toggle table columns
        function toggleTable(tableName) {
            const tableDiv = document.getElementById(`table-${tableName}`);
            if (tableDiv.style.display === 'none') {
                tableDiv.style.display = 'block';
            } else {
                tableDiv.style.display = 'none';
            }
        }
        
        // Copy table structure
        function copyTable(tableName) {
            const schema = currentSchema || currentUploadedSchema;
            if (!schema) return;
            
            const table = schema.tables.find(t => t.name === tableName);
            if (!table) return;
            
            let sql = `CREATE TABLE ${tableName} (\n`;
            const columns = table.columns.map(col => {
                let colDef = `  ${col.name} ${col.type}`;
                if (col.notnull || col.nullable === false) colDef += ' NOT NULL';
                if (col.default) colDef += ` DEFAULT ${col.default}`;
                if (col.pk) colDef += ' PRIMARY KEY';
                return colDef;
            });
            sql += columns.join(',\n') + '\n);';
            
            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(sql).then(() => {
                    toastr.success('تم نسخ هيكل الجدول إلى الحافظة');
                }).catch(() => {
                    fallbackCopyTextToClipboard(sql);
                });
            } else {
                // Fallback for older browsers or HTTP
                fallbackCopyTextToClipboard(sql);
            }
        }
        
        // Fallback copy function for older browsers
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            
            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            textArea.style.opacity = "0";
            
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    toastr.success('تم نسخ هيكل الجدول إلى الحافظة');
                } else {
                    showCopyModal(text);
                }
            } catch (err) {
                showCopyModal(text);
            }
            
            document.body.removeChild(textArea);
        }
        
        // Show copy modal as last resort
        function showCopyModal(text) {
            Swal.fire({
                title: 'انسخ النص التالي',
                html: `<textarea readonly style="width: 100%; height: 200px; font-family: monospace; font-size: 12px;">${text}</textarea>`,
                showCancelButton: true,
                confirmButtonText: 'تم النسخ',
                cancelButtonText: 'إلغاء',
                customClass: {
                    popup: 'swal-wide'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.success('تم نسخ النص بنجاح');
                }
            });
        }
        
        
        // File upload
        document.getElementById('file-upload-area').addEventListener('click', () => {
            document.getElementById('schema-file').click();
        });
        
        document.getElementById('schema-file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                uploadSchemaFile(file);
            }
        });
        
        // Drag and drop
        const uploadArea = document.getElementById('file-upload-area');
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                uploadSchemaFile(files[0]);
            }
        });
        
        // Upload schema file
        function uploadSchemaFile(file) {
            if (file.type !== 'application/json') {
                toastr.error('يرجى اختيار ملف JSON صالح');
                return;
            }
            
            const formData = new FormData();
            formData.append('schema_file', file);
            formData.append('_token', getCSRFToken());
            
            fetch('/dev/upload-schema', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentUploadedSchema = data.schema;
                    displaySchema(data.schema, 'upload-content');
                    document.getElementById('upload-results').style.display = 'block';
                    toastr.success('تم رفع الملف بنجاح');
                } else {
                    toastr.error('خطأ في رفع الملف: ' + data.error);
                }
            })
            .catch(error => {
                toastr.error('خطأ في الاتصال: ' + error.message);
            });
        }
        
        // Execute SQL query
        function executeQuery() {
            const query = document.getElementById('sql-query').value.trim();
            if (!query) {
                toastr.warning('يرجى كتابة استعلام SQL');
                return;
            }
            
            const loading = document.getElementById('query-loading');
            const results = document.getElementById('query-results');
            
            loading.style.display = 'block';
            results.style.display = 'none';
            
            fetch('/dev/execute-query', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCSRFToken()
                },
                body: JSON.stringify({ 
                    query: query,
                    _token: getCSRFToken()
                })
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                results.style.display = 'block';
                
                if (data.success) {
                    displayQueryResults(data, 'query-content');
                    toastr.success('تم تنفيذ الاستعلام بنجاح');
                } else {
                    toastr.error('خطأ في تنفيذ الاستعلام: ' + data.error);
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                toastr.error('خطأ في الاتصال: ' + error.message);
            });
        }
        
        // Display query results
        function displayQueryResults(data, containerId) {
            const container = document.getElementById(containerId);
            let html = '';
            
            if (data.type === 'select' && data.results.length > 0) {
                // Display as table
                const keys = Object.keys(data.results[0]);
                html += `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> تم تنفيذ الاستعلام بنجاح - ${data.count} صف
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    ${keys.map(key => `<th>${key}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${data.results.map(row => `
                                    <tr>
                                        ${keys.map(key => `<td>${row[key] || ''}</td>`).join('')}
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else if (data.type === 'modify') {
                html += `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> ${data.message}
                    </div>
                `;
            } else {
                html += `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> لا توجد نتائج للعرض
                    </div>
                `;
            }
            
            container.innerHTML = html;
            
            // Apply Arabic fonts to newly created elements
            setTimeout(applyArabicFonts, 100);
        }
        
        // Clear query
        function clearQuery() {
            document.getElementById('sql-query').value = '';
            document.getElementById('query-results').style.display = 'none';
        }
        
        // Download schema
        function downloadSchema() {
            if (!currentSchema) {
                toastr.warning('يرجى تحميل هيكل قاعدة البيانات أولاً');
                return;
            }
            
            const dataStr = JSON.stringify(currentSchema, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = `database_schema_${new Date().toISOString().split('T')[0]}.json`;
            link.click();
            
            URL.revokeObjectURL(url);
            toastr.success('تم تحميل ملف JSON بنجاح');
        }
        
        // Load saved schemas
        function loadSavedSchemas() {
            fetch('/dev/saved-schemas', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displaySavedSchemas(data.schemas);
                    } else {
                        toastr.error('خطأ في جلب الملفات المحفوظة: ' + data.error);
                    }
                })
                .catch(error => {
                    toastr.error('خطأ في الاتصال: ' + error.message);
                });
        }
        
        // Display saved schemas
        function displaySavedSchemas(schemas) {
            const container = document.getElementById('saved-schemas');
            let html = '';
            
            if (schemas.length > 0) {
                schemas.forEach(schema => {
                    const date = new Date(schema.created_at * 1000).toLocaleDateString('ar-SA');
                    html += `
                        <div class="schema-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${schema.filename}</h6>
                                    <small class="text-muted">تاريخ الإنشاء: ${date} | عدد الجداول: ${schema.tables_count}</small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary me-2" onclick="loadSchemaFile('${schema.filename}')">
                                        <i class="fas fa-eye"></i> عرض
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSchemaFile('${schema.filename}')">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="alert alert-info">لا توجد ملفات محفوظة</div>';
            }
            
            container.innerHTML = html;
            
            // Apply Arabic fonts to newly created elements
            setTimeout(applyArabicFonts, 100);
        }
        
        // Load schema file
        function loadSchemaFile(filename) {
            fetch(`/dev/saved-schemas/${filename}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCSRFToken()
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentUploadedSchema = data.schema;
                        displaySchema(data.schema, 'upload-content');
                        document.getElementById('upload-results').style.display = 'block';
                        showTab('upload-tab');
                        toastr.success('تم تحميل الملف بنجاح');
                    } else {
                        toastr.error('خطأ في تحميل الملف: ' + data.error);
                    }
                })
                .catch(error => {
                    toastr.error('خطأ في الاتصال: ' + error.message);
                });
        }
        
        // Delete schema file
        function deleteSchemaFile(filename) {
            Swal.fire({
                title: 'تأكيد الحذف',
                text: 'هل أنت متأكد من حذف هذا الملف؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dev/delete-schema/${filename}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': getCSRFToken(),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _token: getCSRFToken()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success('تم حذف الملف بنجاح');
                            loadSavedSchemas();
                        } else {
                            toastr.error('خطأ في حذف الملف: ' + data.error);
                        }
                    })
                    .catch(error => {
                        toastr.error('خطأ في الاتصال: ' + error.message);
                    });
                }
            });
        }
        
        // Toggle selection mode
        function toggleSelectionMode() {
            isSelectionMode = !isSelectionMode;
            selectedTables.clear();
            updateSelectionControls();
            
            if (currentSchema) {
                displaySchema(currentSchema, 'schema-content');
            }
            
            toastr.info(isSelectionMode ? 'تم تفعيل وضع الاختيار' : 'تم إلغاء وضع الاختيار');
        }
        
        // Toggle view mode
        function toggleViewMode() {
            isGridView = !isGridView;
            
            if (currentSchema) {
                displaySchema(currentSchema, 'schema-content');
            }
            
            toastr.info(isGridView ? 'تم التبديل إلى عرض الشبكة' : 'تم التبديل إلى العرض التقليدي');
        }
        
        // Toggle table selection
        function toggleTableSelection(tableName) {
            if (selectedTables.has(tableName)) {
                selectedTables.delete(tableName);
            } else {
                selectedTables.add(tableName);
            }
            
            updateSelectionControls();
            updateTableSelection(tableName);
        }
        
        // Update table selection visual
        function updateTableSelection(tableName) {
            const tableElement = document.querySelector(`[data-table="${tableName}"]`);
            if (tableElement) {
                if (selectedTables.has(tableName)) {
                    tableElement.classList.add('selected');
                } else {
                    tableElement.classList.remove('selected');
                }
            }
        }
        
        // Update selection controls
        function updateSelectionControls() {
            const controls = document.getElementById('selection-controls');
            const count = document.getElementById('selection-count');
            
            if (isSelectionMode) {
                controls.classList.add('show');
                count.textContent = `${selectedTables.size} جدول محدد`;
            } else {
                controls.classList.remove('show');
            }
        }
        
        // Select all tables
        function selectAllTables() {
            if (currentSchema) {
                currentSchema.tables.forEach(table => {
                    selectedTables.add(table.name);
                });
                displaySchema(currentSchema, 'schema-content');
                updateSelectionControls();
            }
        }
        
        // Deselect all tables
        function deselectAllTables() {
            selectedTables.clear();
            if (currentSchema) {
                displaySchema(currentSchema, 'schema-content');
            }
            updateSelectionControls();
        }
        
        // Copy selected tables
        function copySelectedTables() {
            if (selectedTables.size === 0) {
                toastr.warning('يرجى تحديد جداول للنسخ');
                return;
            }
            
            if (!currentSchema) {
                toastr.error('لا توجد بيانات للنسخ');
                return;
            }
            
            let sql = '';
            selectedTables.forEach(tableName => {
                const table = currentSchema.tables.find(t => t.name === tableName);
                if (table) {
                    sql += `-- Table: ${tableName}\n`;
                    sql += `CREATE TABLE ${tableName} (\n`;
                    const columns = table.columns.map(col => {
                        let colDef = `  ${col.name} ${col.type}`;
                        if (col.notnull || col.nullable === false) colDef += ' NOT NULL';
                        if (col.default) colDef += ` DEFAULT ${col.default}`;
                        if (col.pk) colDef += ' PRIMARY KEY';
                        return colDef;
                    });
                    sql += columns.join(',\n') + '\n);\n\n';
                }
            });
            
            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(sql).then(() => {
                    toastr.success(`تم نسخ ${selectedTables.size} جدول إلى الحافظة`);
                }).catch(() => {
                    fallbackCopyTextToClipboard(sql);
                });
            } else {
                fallbackCopyTextToClipboard(sql);
            }
        }
        
        // Filter by alphabet
        function filterByAlphabet(letter) {
            currentAlphabetFilter = letter;
            currentPage = 1; // Reset to first page
            
            // Update filter buttons
            document.querySelectorAll('.alphabet-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Filter tables
            if (currentSchema) {
                const filteredTables = currentSchema.tables.filter(table => {
                    if (letter === 'all') return true;
                    
                    const tableName = table.name.toLowerCase();
                    const firstChar = tableName.charAt(0);
                    
                    return firstChar === letter;
                });
                
                const filteredSchema = {
                    ...currentSchema,
                    tables: filteredTables
                };
                
                displaySchema(filteredSchema, 'schema-content');
            }
        }
        
        // Change page
        function changePage(direction) {
            const totalPages = Math.ceil(filteredTables.length / itemsPerPage);
            const newPage = currentPage + direction;
            
            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                if (currentSchema) {
                    displaySchema(currentSchema, 'schema-content');
                }
            }
        }
        
        // Go to specific page
        function goToPage(page) {
            const totalPages = Math.ceil(filteredTables.length / itemsPerPage);
            
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                if (currentSchema) {
                    displaySchema(currentSchema, 'schema-content');
                }
            }
        }
        
        // Optimized search with debouncing
        let searchTimeout;
        function setupSearch(searchInputId, containerId) {
            const searchInput = document.getElementById(searchInputId);
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch(this.value, containerId);
                }, 300); // 300ms delay
            });
        }
        
        // Perform search
        function performSearch(searchTerm, containerId) {
            if (!currentSchema) return;
            
            const term = searchTerm.toLowerCase();
            const filteredTables = currentSchema.tables.filter(table => {
                const tableName = table.name.toLowerCase();
                const columnNames = table.columns.map(col => col.name.toLowerCase()).join(' ');
                
                return tableName.includes(term) || columnNames.includes(term);
            });
            
            const filteredSchema = {
                ...currentSchema,
                tables: filteredTables
            };
            
            currentPage = 1; // Reset to first page
            displaySchema(filteredSchema, containerId);
        }
        
        // Initialize search functionality
        document.addEventListener('DOMContentLoaded', function() {
            setupSearch('schema-search', 'schema-content');
            setupSearch('upload-search', 'upload-content');
            
            // Apply Arabic fonts on page load
            applyArabicFonts();
        });
    </script>
</body>
</html>
