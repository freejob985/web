<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم الأدمن</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Changa:wght@200..800&display=swap" rel="stylesheet">
    
    <!-- Toast JS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css">
    
    <!-- Admin Settings CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-settings.css') }}">
    
    <!-- Custom CSS from Settings -->
    @php
        $designSettings = \App\Models\Setting::where('group', 'design')->get()->keyBy('key');
        $adminCustomCSS = $designSettings->get('admin_custom_css')?->value ?? '';
    @endphp
    @if($adminCustomCSS)
    <style>
        {!! $adminCustomCSS !!}
    </style>
    @endif
    
    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --sidebar-active: #3498db;
            --topbar-bg: #ffffff;
            --content-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--content-bg);
            overflow-x: hidden;
        }

        /* Font Awesome Icons Priority */
        .fas, .fa, .far, .fab, .fal, .fad {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
            font-weight: 900 !important;
        }

        .far {
            font-weight: 400 !important;
        }

        .fab {
            font-family: "Font Awesome 6 Brands" !important;
            font-weight: 400 !important;
        }

        /* Ensure icons display properly */
        i[class*="fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
            font-weight: 900 !important;
            font-style: normal !important;
        }

        i[class*="far"] {
            font-weight: 400 !important;
        }

        i[class*="fab"] {
            font-family: "Font Awesome 6 Brands" !important;
            font-weight: 400 !important;
        }
        
        #sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #1a2530);
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            position: relative;
            width: 280px;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Prevent flash on page load */
        #sidebar:not(.collapsed) {
            width: 280px;
        }
        
        #sidebar.collapsed {
            width: 70px;
        }
        
        #sidebar.collapsed .sidebar-header h3,
        #sidebar.collapsed ul li a span {
            display: none;
        }
        
        #sidebar.collapsed ul li a {
            padding: 18px 15px;
            text-align: center;
            border-radius: 15px;
            margin-left: 8px;
        }
        
        #sidebar.collapsed ul li a i {
            margin-left: 0;
            font-size: 1.3rem;
            display: block;
        }

        #sidebar.collapsed .sidebar-header {
            padding: 20px 10px;
        }

        #sidebar.collapsed .sidebar-header i {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }
        
        #sidebar::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="30" r="0.7" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="70" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="60" r="0.6" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="40" r="0.65" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 150px 150px;
            z-index: 0;
        }
        
        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.3), rgba(52, 152, 219, 0.2));
            text-align: center;
            position: relative;
            z-index: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #sidebar .sidebar-header h3 {
            color: #fff;
            font-size: 1.4rem;
            margin: 0;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        #sidebar .sidebar-header i {
            font-size: 1.8rem;
            margin-bottom: 10px;
            display: block;
            color: #3498db;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        /* Sidebar menu wrapper with smooth scroll */
        .sidebar-menu-wrapper {
            height: calc(100vh - 120px);
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            z-index: 1;
        }
        
        /* Smooth scrollbar styling */
        .sidebar-menu-wrapper::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-menu-wrapper::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        .sidebar-menu-wrapper::-webkit-scrollbar-thumb {
            background: rgba(52, 152, 219, 0.6);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(52, 152, 219, 0.8);
        }
        
        /* Firefox scrollbar */
        .sidebar-menu-wrapper {
            scrollbar-width: thin;
            scrollbar-color: rgba(52, 152, 219, 0.6) rgba(0, 0, 0, 0.2);
        }
        
        /* Smooth scroll behavior */
        .sidebar-menu-wrapper {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch; /* iOS smooth scrolling */
        }
        
        /* Sidebar collapsed scrollbar */
        #sidebar.collapsed .sidebar-menu-wrapper {
            height: calc(100vh - 100px);
        }
        
        #sidebar.collapsed .sidebar-menu-wrapper::-webkit-scrollbar {
            width: 4px;
        }
        
        /* Hide scrollbar on mobile */
        @media (max-width: 768px) {
            .sidebar-menu-wrapper::-webkit-scrollbar {
                width: 3px;
            }
        }
        
        #sidebar ul.components {
            padding: 25px 0;
            position: relative;
            z-index: 1;
        }
        
        #sidebar ul li a {
            padding: 15px 20px;
            font-size: 0.95rem;
            display: block;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 25px 0 0 25px;
            margin-left: 15px;
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }
        
        #sidebar ul li a::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: #3498db;
            transform: scaleY(0);
            transition: transform 0.3s;
        }
        
        #sidebar ul li a:hover {
            color: #fff;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.4), rgba(46, 204, 113, 0.2));
            transform: translateX(-8px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        #sidebar ul li a:hover::before {
            transform: scaleY(1);
        }
        
        #sidebar ul li.active > a {
            background: rgba(52, 152, 219, 0.4);
            color: white;
        }
        
        #sidebar ul li.active > a::before {
            transform: scaleY(1);
        }
        
        #sidebar ul li a i {
            margin-left: 12px;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        #sidebar ul li a:hover i {
            transform: scale(1.1);
            color: #3498db;
        }
        
        /* Submenu styles */
        #sidebar .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background: rgba(0, 0, 0, 0.15);
            margin: 5px 0 10px 0;
            padding: 0 15px;
            overflow: hidden;
            display: none;
        }
        
        #sidebar .submenu.show {
            display: block;
        }
        
        #sidebar .submenu li {
            position: relative;
        }
        
        #sidebar .submenu li a {
            padding: 10px 20px 10px 40px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.75);
            border-radius: 0;
            margin: 0;
            position: relative;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            border: none;
        }
        
        #sidebar .submenu li a:hover {
            background: rgba(52, 152, 219, 0.25);
            color: #fff;
            transform: translateX(0);
        }
        
        #sidebar .submenu li a i {
            margin-left: 10px;
            width: 20px;
            text-align: center;
            font-size: 0.85rem;
            color: #3498db;
            transition: all 0.2s ease;
        }
        
        #sidebar .submenu li a:hover i {
            transform: scale(1.1);
            color: #fff;
        }
        
        #sidebar .submenu li a::before {
            content: "";
            position: absolute;
            top: 50%;
            right: 25px;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: #3498db;
            border-radius: 50%;
        }
        
        #sidebar .has-submenu > a::after {
            content: "\f107";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            float: left;
            transition: transform 0.3s ease;
            margin-top: 3px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        #sidebar .has-submenu.active > a::after {
            transform: rotate(180deg);
        }
        
        #sidebar .has-submenu.active > .submenu {
            display: block;
        }
        
        /* Main menu item spacing */
        #sidebar ul.components > li {
            margin-bottom: 5px;
        }
        
        #sidebar ul.components > li:last-child {
            margin-bottom: 0;
        }
        
        #content {
            min-height: 100vh;
            transition: all 0.3s;
            padding-top: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            flex: 1;
        }
        
        .topbar {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            padding: 20px 25px;
            margin-bottom: 30px;
            border-radius: 20px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            left: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .notification-dropdown {
            position: relative;
            display: inline-block;
            z-index: 10000000 !important;
        }
        
        .notification-icon {
            position: relative;
            font-size: 1.4rem;
            color: #2c3e50;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.3s;
        }
        
        .notification-icon:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        
        .notification-panel {
            position: fixed !important;
            top: 80px !important;
            right: 20px !important;
            width: 420px;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            z-index: 999999999 !important;
            display: none;
            max-height: 75vh;
            overflow: hidden;
            border: 2px solid rgba(52, 152, 219, 0.2);
            transform: translateY(-10px) scale(0.95);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            margin-top: 0;
            pointer-events: none;
            backdrop-filter: blur(20px);
        }
        
        .notification-panel.show {
            display: block !important;
            transform: translateY(0) scale(1) !important;
            opacity: 1 !important;
            z-index: 999999999 !important;
            pointer-events: auto !important;
            animation: notificationSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        @keyframes notificationSlideIn {
            0% {
                transform: translateY(-20px) scale(0.9);
                opacity: 0;
            }
            50% {
                transform: translateY(5px) scale(1.02);
            }
            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }
        
        .notification-header {
            padding: 20px 25px;
            border-bottom: 2px solid rgba(52, 152, 219, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: hidden;
        }
        
        .notification-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 80px 80px;
            z-index: 0;
        }
        
        .notification-header * {
            position: relative;
            z-index: 1;
        }
        
        .notification-header h6 {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .notification-header a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .notification-header a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .notification-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px 0;
        }
        
        .notification-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .notification-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .notification-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 10px;
        }
        
        .notification-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #2980b9, #1f618d);
        }
        
        .notification-item {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
            margin: 0 10px;
            border-radius: 12px;
            margin-bottom: 8px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .notification-item:hover {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.05) 0%, rgba(46, 204, 113, 0.03) 100%);
            transform: translateX(-5px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.15);
        }
        
        .notification-item.unread {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-left: 4px solid #3498db;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
        }
        
        .notification-item.unread::before {
            content: "";
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: #e74c3c;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        .notification-item .title {
            font-weight: 700;
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        .notification-item .time {
            font-size: 0.75rem;
            color: #7f8c8d;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .notification-item .text-muted {
            color: #5a6c7d !important;
            font-size: 0.85rem;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .notification-icon-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .notification-icon-small i {
            color: white;
            font-size: 1rem;
        }
        
        .notification-item:hover .notification-icon-small {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .unread-indicator {
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 6px;
        }
        
        .notification-footer {
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }
        
        /* ضمان عدم تداخل العناصر الأخرى مع الإشعارات */
        .notification-panel * {
            z-index: inherit !important;
        }
        
        /* إخفاء العناصر التي قد تتداخل مع الإشعارات */
        .notification-panel.show ~ * {
            z-index: 1 !important;
        }
        
        /* ضمان ظهور الإشعارات فوق جميع العناصر */
        body.notification-open .notification-panel {
            z-index: 9999999 !important;
        }
        
        /* إخفاء العناصر التي قد تتداخل مع الإشعارات */
        .notification-panel.show {
            z-index: 9999999 !important;
            position: fixed !important;
        }
        
        /* ضمان عدم تداخل الـ modals والـ dropdowns */
        .modal, .dropdown-menu, .popover, .tooltip {
            z-index: 1000 !important;
        }
        
        /* ضمان ظهور الإشعارات فوق كل شيء */
        .notification-panel.show {
            z-index: 9999999 !important;
            position: fixed !important;
            top: 80px !important;
            right: 20px !important;
        }
        
        /* إخفاء العناصر التي قد تتداخل مع الإشعارات */
        .notification-panel.show ~ *,
        .notification-panel.show + * {
            z-index: 1 !important;
        }
        
        /* ضمان عدم تداخل الـ sidebar والـ content */
        #sidebar, #content, .main-content {
            z-index: 1 !important;
        }
        
        /* ضمان ظهور الإشعارات فوق الـ header */
        .topbar, .navbar, .header {
            z-index: 1000 !important;
        }
        
        /* CSS نهائي لضمان عدم تداخل العناصر */
        .notification-panel {
            z-index: 9999999 !important;
            position: fixed !important;
            top: 80px !important;
            right: 20px !important;
        }
        
        .notification-panel.show {
            z-index: 9999999 !important;
            position: fixed !important;
            top: 80px !important;
            right: 20px !important;
            display: block !important;
        }
        
        /* إخفاء جميع العناصر الأخرى عند ظهور الإشعارات */
        body.notification-open *:not(.notification-panel):not(.notification-panel *) {
            z-index: 1 !important;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .notification-item {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .notification-item:hover {
            background: rgba(52, 152, 219, 0.05);
        }
        
        .notification-footer {
            padding: 20px 25px;
            text-align: center;
            border-top: 2px solid rgba(52, 152, 219, 0.1);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0 0 20px 20px;
        }
        
        .notification-footer .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .notification-footer .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
            background: linear-gradient(135deg, #2980b9 0%, #1f618d 100%);
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 25px;
            transition: all 0.3s;
            border: 1px solid rgba(0, 0, 0, 0.05);
            background: white;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 700;
            padding: 15px 20px;
        }
        
        .btn {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .btn::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(50, 50);
                opacity: 0;
            }
        }
        
        .btn-primary {
            background: linear-gradient(120deg, #2c3e50, #3498db);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-danger {
            background: linear-gradient(120deg, #c0392b, #e74c3c);
            border: none;
        }
        
        /* Enhanced Table Styling */
        .table {
            box-shadow: none;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }
        
        .table thead th {
            border-top: none;
            font-weight: 700;
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px 12px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
        }
        
        .table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
        }
        
        .table tbody td {
            padding: 15px 12px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .table-hover tbody tr:hover {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.08) 0%, rgba(46, 204, 113, 0.05) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        /* Enhanced Badge Styling */
        .badge {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }
        
        .badge-info {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }
        
        /* Enhanced Button Group */
        .btn-group .btn {
            margin: 0 2px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Statistics Cards */
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .stats-card .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .stats-card .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Enhanced Pagination */
        .pagination {
            justify-content: center;
            margin-top: 30px;
            gap: 5px;
        }
        
        .pagination .page-link {
            border: none;
            color: #2c3e50;
            background: white;
            margin: 0;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            min-width: 45px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .pagination .page-link:hover {
            background: linear-gradient(135deg, #3498db, #2ecc71);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .pagination .page-item.disabled .page-link {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            background: #f8f9fa;
            color: #6c757d;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Pagination Info */
        .pagination-info {
            text-align: center;
            margin-top: 15px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .pagination-info strong {
            color: #2c3e50;
        }
        
        /* Table Header with Statistics */
        .table-header-stats {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px 10px 0 0;
            border-bottom: 3px solid #3498db;
        }
        
        .table-header-stats h3 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
        }
        
        .table-header-stats .stats-summary {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        
        .table-header-stats .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .table-header-stats .stat-item i {
            color: #3498db;
        }
        
        /* Enhanced Card Headers */
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid #3498db;
            font-weight: 700;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h3 {
            color: #2c3e50;
            margin: 0;
            font-size: 1.3rem;
        }
        
        /* Enhanced Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .empty-state h4 {
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #adb5bd;
            margin-bottom: 30px;
        }
        
        .navbar-toggler {
            border: none;
            outline: none !important;
            box-shadow: none !important;
            background: linear-gradient(120deg, #2c3e50, #3498db);
            color: white;
            border-radius: 10px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none !important;
        }
        
        .navbar-toggler i {
            font-size: 1.2rem;
        }
        
        .user-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
            background: linear-gradient(135deg, #34495e, #2980b9);
        }

        .user-badge i {
            font-size: 0.8rem;
            margin-left: 6px;
        }
        
        .component-spacing {
            margin-bottom: 30px;
        }
        
        .component-spacing .card {
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            #sidebar {
                width: 80px;
                text-align: center;
            }
            
            #sidebar .sidebar-header h3,
            #sidebar ul li a span {
                display: none;
            }
            
            #sidebar ul li a i {
                margin-left: 0;
                display: block;
                font-size: 1.2rem;
                margin-bottom: 5px;
            }
            
            #sidebar ul li a {
                padding: 20px 8px;
                border-radius: 15px;
                margin-left: 5px;
            }

            .user-badge {
                font-size: 0.75rem;
                padding: 6px 12px;
            }

            .user-badge i {
                font-size: 0.7rem;
            }
            
            .topbar {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .notification-panel {
                left: auto;
                right: 0;
                width: 300px;
                max-width: calc(100vw - 20px);
            }
        }
        
        .wrapper {
            display: flex;
            flex-direction: row-reverse;
            min-height: 100vh;
        }
        
        /* Fix layout positioning */
        #content {
            flex: 1;
            min-height: 100vh;
            position: relative;
        }
        
        #sidebar {
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            z-index: 1050;
        }
        
        /* Adjust content margin when sidebar is visible */
        body:not(.sidebar-collapsed) #content {
            margin-right: 280px;
        }
        
        body.sidebar-collapsed #content {
            margin-right: 70px;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
            }
            
            #sidebar.show {
                transform: translateX(0);
            }
            
            body #content {
                margin-right: 0 !important;
            }
        }

        /* Enhanced Global Loading Overlay Styles */
        .global-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.95) 0%, rgba(46, 204, 113, 0.95) 100%);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(20px);
            animation: loadingFadeIn 0.5s ease-out;
        }

        @keyframes loadingFadeIn {
            from {
                opacity: 0;
                backdrop-filter: blur(0px);
            }
            to {
                opacity: 1;
                backdrop-filter: blur(20px);
            }
        }

        .loading-content {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            transform: scale(0.9);
            animation: loadingContentIn 0.6s ease-out 0.2s forwards;
        }

        @keyframes loadingContentIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .loading-animation {
            width: 150px;
            height: 150px;
            margin-bottom: 25px;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
        }

        .loading-content h3 {
            font-family: 'Cairo', sans-serif;
            font-size: 24px;
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .loading-content p {
            font-family: 'Cairo', sans-serif;
            font-size: 16px;
            color: #7f8c8d;
            margin: 0;
            font-weight: 500;
        }

        .loading-progress {
            width: 200px;
            height: 4px;
            background: rgba(52, 152, 219, 0.2);
            border-radius: 2px;
            margin: 20px auto 0;
            overflow: hidden;
        }

        .loading-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2ecc71, #e74c3c, #f39c12);
            background-size: 200% 100%;
            border-radius: 2px;
            animation: loadingProgress 2s ease-in-out infinite;
        }

        @keyframes loadingProgress {
            0% {
                width: 0%;
                background-position: 0% 50%;
            }
            50% {
                width: 70%;
                background-position: 100% 50%;
            }
            100% {
                width: 100%;
                background-position: 0% 50%;
            }
        }
    </style>
</head>
<body>
    <!-- Enhanced Global Loading Overlay -->
    <div id="global-loading-overlay" class="global-loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="loading-animation">
                <svg width="150" height="150" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                  <defs>
                    <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                      <stop offset="0%" style="stop-color:#3498db;stop-opacity:1" />
                      <stop offset="50%" style="stop-color:#2ecc71;stop-opacity:1" />
                      <stop offset="100%" style="stop-color:#e74c3c;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                      <stop offset="0%" style="stop-color:#f39c12;stop-opacity:1" />
                      <stop offset="50%" style="stop-color:#9b59b6;stop-opacity:1" />
                      <stop offset="100%" style="stop-color:#1abc9c;stop-opacity:1" />
                    </linearGradient>
                  </defs>
                  
                  <!-- Store Icon Background -->
                  <circle cx="60" cy="60" r="50" fill="url(#gradient1)" opacity="0.1">
                    <animate attributeName="r" values="45;55;45" dur="2s" repeatCount="indefinite"/>
                  </circle>
                  
                  <!-- Store Building -->
                  <rect x="35" y="45" width="50" height="35" fill="url(#gradient1)" rx="5">
                    <animateTransform attributeName="transform" type="scale" values="1;1.05;1" dur="2s" repeatCount="indefinite"/>
                  </rect>
                  
                  <!-- Store Roof -->
                  <polygon points="30,45 60,25 90,45" fill="url(#gradient2)">
                    <animateTransform attributeName="transform" type="scale" values="1;1.1;1" dur="2s" repeatCount="indefinite"/>
                  </polygon>
                  
                  <!-- Store Door -->
                  <rect x="52" y="60" width="16" height="20" fill="#34495e" rx="2">
                    <animate attributeName="fill" values="#34495e;#2c3e50;#34495e" dur="1.5s" repeatCount="indefinite"/>
                  </rect>
                  
                  <!-- Store Windows -->
                  <rect x="38" y="52" width="8" height="8" fill="#f1c40f" rx="1">
                    <animate attributeName="opacity" values="0.5;1;0.5" dur="1s" repeatCount="indefinite"/>
                  </rect>
                  <rect x="74" y="52" width="8" height="8" fill="#f1c40f" rx="1">
                    <animate attributeName="opacity" values="1;0.5;1" dur="1s" repeatCount="indefinite"/>
                  </rect>
                  
                  <!-- Shopping Cart -->
                  <g transform="translate(20,85)">
                    <rect x="0" y="0" width="15" height="10" fill="none" stroke="url(#gradient1)" stroke-width="2" rx="2">
                      <animateTransform attributeName="transform" type="translate" values="0,0;5,0;0,0" dur="3s" repeatCount="indefinite"/>
                    </rect>
                    <circle cx="5" cy="15" r="2" fill="url(#gradient1)">
                      <animateTransform attributeName="transform" type="rotate" values="0;360;0" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="12" cy="15" r="2" fill="url(#gradient1)">
                      <animateTransform attributeName="transform" type="rotate" values="0;360;0" dur="2s" repeatCount="indefinite"/>
                    </circle>
                  </g>
                  
                  <!-- Loading Dots -->
                  <circle cx="45" cy="95" r="3" fill="url(#gradient2)">
                    <animate attributeName="opacity" values="0.3;1;0.3" dur="1.5s" begin="0s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="60" cy="95" r="3" fill="url(#gradient2)">
                    <animate attributeName="opacity" values="0.3;1;0.3" dur="1.5s" begin="0.5s" repeatCount="indefinite"/>
                  </circle>
                  <circle cx="75" cy="95" r="3" fill="url(#gradient2)">
                    <animate attributeName="opacity" values="0.3;1;0.3" dur="1.5s" begin="1s" repeatCount="indefinite"/>
                  </circle>
                  
                  <!-- Rotating Ring -->
                  <circle cx="60" cy="60" r="55" fill="none" stroke="url(#gradient1)" stroke-width="2" stroke-dasharray="10,5" opacity="0.3">
                    <animateTransform attributeName="transform" type="rotate" values="0 60 60;360 60 60" dur="4s" repeatCount="indefinite"/>
                  </circle>
                </svg>
            </div>
            <h3>إنجب ستور</h3>
            <p>جاري تحميل لوحة التحكم...</p>
            <div class="loading-progress">
                <div class="loading-progress-bar"></div>
            </div>
        </div>
    </div>

    <div class="wrapper d-flex align-items-stretch">
        <!-- Page Content -->
        <div id="content" class="flex-grow-1">
            <div class="container-fluid">
                <div class="topbar d-flex justify-content-between align-items-center">
                    <div class="gap-4 d-flex align-items-center">
                        <button type="button" id="sidebarCollapse" class="btn btn-primary">
                            <i class="fas fa-bars"></i>
                            <span class="ms-2">القائمة</span>
                        </button>
                        
                        <!-- Notification Panel -->
                        <div class="notification-dropdown">
                            <div class="notification-icon" onclick="toggleNotificationPanel()">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge" id="topbar-notification-count">2</span>
                            </div>
                            <div class="notification-panel">
                                <div class="notification-header">
                                    <h6 class="mb-0">الإشعارات</h6>
                                    <a href="#" class="text-primary" onclick="markAllAsRead()">وضع علامة كمقروء</a>
                                </div>
                                <div class="notification-list" id="notification-list">
                                    <!-- Sample notifications -->
                                    <div class="notification-item unread" onclick="markAsRead(1)">
                                        <div class="gap-3 d-flex align-items-start">
                                            <div class="notification-icon-small">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="title">طلب جديد</div>
                                                <div class="text-muted">تم استلام طلب جديد من العميل أحمد محمد</div>
                                                <div class="time">منذ 5 دقائق</div>
                                            </div>
                                            <div class="unread-indicator"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-item" onclick="markAsRead(2)">
                                        <div class="gap-3 d-flex align-items-start">
                                            <div class="notification-icon-small">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="title">منتج جديد</div>
                                                <div class="text-muted">تم إضافة منتج جديد: تفاح أحمر طازج</div>
                                                <div class="time">منذ ساعة</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-item" onclick="markAsRead(3)">
                                        <div class="gap-3 d-flex align-items-start">
                                            <div class="notification-icon-small">
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="title">تقييم جديد</div>
                                                <div class="text-muted">تقييم 5 نجوم من العميل فاطمة علي</div>
                                                <div class="time">منذ 3 ساعات</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-item unread" onclick="markAsRead(4)">
                                        <div class="gap-3 d-flex align-items-start">
                                            <div class="notification-icon-small">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="title">تنبيه المخزون</div>
                                                <div class="text-muted">منتج "خيار طازج" نفد من المخزون</div>
                                                <div class="time">منذ 6 ساعات</div>
                                            </div>
                                            <div class="unread-indicator"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 notification-footer border-top">
                                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-primary btn-sm w-100">عرض جميع الإشعارات</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gap-3 d-flex align-items-center">
                        <div class="user-badge">
                            <i class="fas fa-user me-2"></i> {{ auth()->user()->name ?? 'Admin' }}
                        </div>
                        
                        <form method="POST" action="{{ route('admin.logout') }}" class="mb-0">
                            @csrf
                            <button class="btn btn-danger" type="submit">
                                <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="component-spacing">
                    @yield('content')
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-user-shield"></i>
                <h3>لوحة التحكم</h3>
            </div>
            
            <div class="sidebar-menu-wrapper">
                <ul class="list-unstyled components">
                <!-- الصفحة الرئيسية -->
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <span>الرئيسية</span>
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                
                <!-- ════════════════════════════════════════════════════════════════ -->
                <!-- القوائم الرئيسية (مع Submenus) -->
                <!-- ════════════════════════════════════════════════════════════════ -->
                
                <!-- إدارة الطلبات -->
                <li class="has-submenu {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="#ordersSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}">
                        <span>إدارة الطلبات</span>
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.orders.*') ? 'show' : '' }}" id="ordersSubmenu">
                        <li class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.orders.index') }}"><i class="fas fa-list"></i> جميع الطلبات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.orders.pending') ? 'active' : '' }}">
                            <a href="{{ route('admin.orders.pending') }}"><i class="fas fa-clock"></i> الطلبات المعلقة</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.orders.completed') ? 'active' : '' }}">
                            <a href="{{ route('admin.orders.completed') }}"><i class="fas fa-check-circle"></i> الطلبات المكتملة</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة العروض -->
                <li class="has-submenu {{ request()->routeIs('admin.offers.*') || request()->routeIs('admin.offer-categories.*') ? 'active' : '' }}">
                    <a href="#offersSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.offers.*') || request()->routeIs('admin.offer-categories.*') ? 'true' : 'false' }}">
                        <span>إدارة العروض</span>
                        <i class="fas fa-percentage"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.offers.*') || request()->routeIs('admin.offer-categories.*') ? 'show' : '' }}" id="offersSubmenu">
                        <li class="{{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.offers.index') }}"><i class="fas fa-tags"></i> جميع العروض</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.offers.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.offers.create') }}"><i class="fas fa-plus-circle"></i> إضافة عرض جديد</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.offer-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.offer-categories.index') }}"><i class="fas fa-layer-group"></i> أقسام العروض</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.offer-categories.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.offer-categories.create') }}"><i class="fas fa-plus-circle"></i> إضافة قسم جديد</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة الكوبونات -->
                <li class="has-submenu {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <a href="#couponsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.coupons.*') ? 'true' : 'false' }}">
                        <span>إدارة الكوبونات</span>
                        <i class="fas fa-ticket-alt"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.coupons.*') ? 'show' : '' }}" id="couponsSubmenu">
                        <li class="{{ request()->routeIs('admin.coupons.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.coupons.index') }}"><i class="fas fa-list"></i> جميع الكوبونات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.coupons.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.coupons.create') }}"><i class="fas fa-plus-circle"></i> إضافة كوبون جديد</a>
                        </li>
                    </ul>
                </li>
                
                <!-- ════════════════════════════════════════════════════════════════ -->
                <!-- إدارة المنتجات والمخزون -->
                <!-- ════════════════════════════════════════════════════════════════ -->
                
                <!-- إدارة المنتجات -->
                <li class="has-submenu {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="#productsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.products.*') ? 'true' : 'false' }}">
                        <span>إدارة المنتجات</span>
                        <i class="fas fa-box"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.products.*') ? 'show' : '' }}" id="productsSubmenu">
                        <li class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.products.index') }}"><i class="fas fa-list"></i> جميع المنتجات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.products.create') }}"><i class="fas fa-plus-circle"></i> إضافة منتج جديد</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.products.import') ? 'active' : '' }}">
                            <a href="{{ route('admin.products.import') }}"><i class="fas fa-file-import"></i> استيراد المنتجات</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة الأقسام والماركات -->
                <li class="has-submenu {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <a href="#categoriesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') ? 'true' : 'false' }}">
                        <span>الأقسام والماركات</span>
                        <i class="fas fa-layer-group"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.brands.*') ? 'show' : '' }}" id="categoriesSubmenu">
                        <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.categories.index') }}"><i class="fas fa-th-large"></i> الأقسام الرئيسية</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.subcategories.index') }}"><i class="fas fa-stream"></i> الأقسام الفرعية</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.brands.index') }}"><i class="fas fa-star"></i> الماركات</a>
                        </li>
                    </ul>
                </li>
                
                <!-- ════════════════════════════════════════════════════════════════ -->
                <!-- إدارة الموردين والأعمال -->
                <!-- ════════════════════════════════════════════════════════════════ -->
                
                <!-- إدارة الموردين -->
                <li class="has-submenu {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                    <a href="#vendorsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.vendors.*') ? 'true' : 'false' }}">
                        <span>إدارة الموردين</span>
                        <i class="fas fa-store"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.vendors.*') ? 'show' : '' }}" id="vendorsSubmenu">
                        <li class="{{ request()->routeIs('admin.vendors.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.vendors.index') }}"><i class="fas fa-list"></i> جميع الموردين</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.vendors.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.vendors.create') }}"><i class="fas fa-plus-circle"></i> إضافة مورد جديد</a>
                        </li>
                    </ul>
                </li>
                
                <!-- فئات الأعمال -->
                <li class="has-submenu {{ request()->routeIs('admin.business-categories.*') ? 'active' : '' }}">
                    <a href="#businessCategoriesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.business-categories.*') ? 'true' : 'false' }}">
                        <span>فئات الأعمال</span>
                        <i class="fas fa-briefcase"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.business-categories.*') ? 'show' : '' }}" id="businessCategoriesSubmenu">
                        <li class="{{ request()->routeIs('admin.business-categories.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.business-categories.index') }}"><i class="fas fa-list"></i> جميع الفئات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.business-categories.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.business-categories.create') }}"><i class="fas fa-plus-circle"></i> إضافة فئة جديدة</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة صفحة من نحن -->
                <li class="has-submenu {{ request()->routeIs('admin.about-pages.*') ? 'active' : '' }}">
                    <a href="#aboutPagesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.about-pages.*') ? 'true' : 'false' }}">
                        <span>صفحة من نحن</span>
                        <i class="fas fa-info-circle"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.about-pages.*') ? 'show' : '' }}" id="aboutPagesSubmenu">
                        <li class="{{ request()->routeIs('admin.about-pages.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.index') }}"><i class="fas fa-list"></i> جميع الأقسام</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.about-pages.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.create') }}"><i class="fas fa-plus-circle"></i> إضافة محتوى جديد</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.about-pages.section', 'hero') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.section', 'hero') }}"><i class="fas fa-home"></i> القسم الرئيسي</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.about-pages.section', 'story') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.section', 'story') }}"><i class="fas fa-book"></i> قصتنا</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.about-pages.section', 'values') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.section', 'values') }}"><i class="fas fa-heart"></i> قيمنا</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.about-pages.section', 'statistics') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.section', 'statistics') }}"><i class="fas fa-chart-bar"></i> أرقامنا تتحدث</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.about-pages.section', 'team') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.section', 'team') }}"><i class="fas fa-users"></i> فريقنا</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.about-pages.section', 'mission') ? 'active' : '' }}">
                            <a href="{{ route('admin.about-pages.section', 'mission') }}"><i class="fas fa-target"></i> رسالتنا</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة الصفحات الثابتة -->
                <li class="has-submenu {{ request()->routeIs('admin.static-pages.*') ? 'active' : '' }}">
                    <a href="#staticPagesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.static-pages.*') ? 'true' : 'false' }}">
                        <span>الصفحات الثابتة</span>
                        <i class="fas fa-file-alt"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.static-pages.*') ? 'show' : '' }}" id="staticPagesSubmenu">
                        <li class="{{ request()->routeIs('admin.static-pages.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.static-pages.index') }}"><i class="fas fa-list"></i> جميع الصفحات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.static-pages.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.static-pages.create') }}"><i class="fas fa-plus-circle"></i> إضافة صفحة جديدة</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة الرسائل -->
                <li class="has-submenu {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                    <a href="#messagesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.contact-messages.*') ? 'true' : 'false' }}">
                        <span>إدارة الرسائل</span>
                        <i class="fas fa-envelope"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.contact-messages.*') ? 'show' : '' }}" id="messagesSubmenu">
                        <li class="{{ request()->routeIs('admin.contact-messages.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.contact-messages.index') }}"><i class="fas fa-list"></i> جميع الرسائل</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.contact-messages.unread') ? 'active' : '' }}">
                            <a href="{{ route('admin.contact-messages.unread') }}"><i class="fas fa-envelope"></i> الرسائل غير المقروءة</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.contact-messages.read') ? 'active' : '' }}">
                            <a href="{{ route('admin.contact-messages.read') }}"><i class="fas fa-envelope-open"></i> الرسائل المقروءة</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة الإشعارات -->
                <li class="has-submenu {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <a href="#notificationsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.notifications.*') ? 'true' : 'false' }}">
                        <span>إدارة الإشعارات</span>
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notification-count" style="display: none;">0</span>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.notifications.*') ? 'show' : '' }}" id="notificationsSubmenu">
                        <li class="{{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.notifications.index') }}"><i class="fas fa-list"></i> جميع الإشعارات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.notifications.unread') ? 'active' : '' }}">
                            <a href="{{ route('admin.notifications.unread') }}"><i class="fas fa-bell-slash"></i> الإشعارات غير المقروءة</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.notifications.read') ? 'active' : '' }}">
                            <a href="{{ route('admin.notifications.read') }}"><i class="fas fa-bell"></i> الإشعارات المقروءة</a>
                        </li>
                    </ul>
                </li>
                
                <!-- النشرة الإخبارية -->
                <li class="has-submenu {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}">
                    <a href="#newsletterSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.newsletters.*') ? 'true' : 'false' }}">
                        <span>النشرة الإخبارية</span>
                        <i class="fas fa-newspaper"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.newsletters.*') ? 'show' : '' }}" id="newsletterSubmenu">
                        <li class="{{ request()->routeIs('admin.newsletters.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.newsletters.index') }}"><i class="fas fa-users"></i> المشتركين</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.newsletters.campaigns') ? 'active' : '' }}">
                            <a href="{{ route('admin.newsletters.campaigns') }}"><i class="fas fa-bullhorn"></i> الحملات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.newsletters.campaigns.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.newsletters.campaigns.create') }}"><i class="fas fa-plus-circle"></i> حملة جديدة</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.newsletters.stats') ? 'active' : '' }}">
                            <a href="{{ route('admin.newsletters.stats') }}"><i class="fas fa-chart-bar"></i> الإحصائيات</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة المستخدمين -->
                <li class="has-submenu {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="#usersSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}">
                        <span>إدارة المستخدمين</span>
                        <i class="fas fa-users"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="usersSubmenu">
                        <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}"><i class="fas fa-list"></i> جميع المستخدمين</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.users.admins') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.admins') }}"><i class="fas fa-user-shield"></i> مستخدمو الإدارة</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إدارة المواقع الجغرافية -->
                <li class="has-submenu {{ request()->routeIs('admin.governorates.*') || request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                    <a href="#locationsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.governorates.*') || request()->routeIs('admin.cities.*') ? 'true' : 'false' }}">
                        <span>المواقع الجغرافية</span>
                        <i class="fas fa-map-marker-alt"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.governorates.*') || request()->routeIs('admin.cities.*') ? 'show' : '' }}" id="locationsSubmenu">
                        <li class="{{ request()->routeIs('admin.governorates.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.governorates.index') }}"><i class="fas fa-map"></i> المحافظات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.cities.index') }}"><i class="fas fa-city"></i> المدن</a>
                        </li>
                    </ul>
                </li>
                
                <!-- إعدادات النظام -->
                <li class="has-submenu {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="#settingsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }}">
                        <span>إعدادات النظام</span>
                        <i class="fas fa-cogs"></i>
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('admin.settings.*') ? 'show' : '' }}" id="settingsSubmenu">
                        <li class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.index') }}"><i class="fas fa-list"></i> جميع الإعدادات</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.settings.show', 'general') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.show', 'general') }}"><i class="fas fa-globe"></i> إعدادات الموقع</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.settings.show', 'email') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.show', 'email') }}"><i class="fas fa-envelope"></i> إعدادات البريد</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.settings.show', 'payment') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.show', 'payment') }}"><i class="fas fa-credit-card"></i> إعدادات الدفع</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.settings.show', 'seo') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.show', 'seo') }}"><i class="fas fa-search"></i> إعدادات السيو</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.settings.show', 'design') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.show', 'design') }}"><i class="fas fa-palette"></i> إعدادات التصميم</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.settings.show', 'developer') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.show', 'developer') }}"><i class="fas fa-code"></i> وضع المطور</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.system.maintenance') ? 'active' : '' }}">
                            <a href="{{ route('admin.system.maintenance') }}"><i class="fas fa-tools"></i> صيانة النظام</a>
                        </li>
                    </ul>
                </li>
                
                <!-- ════════════════════════════════════════════════════════════════ -->
                <!-- الصفحات المفردة -->
                <!-- ════════════════════════════════════════════════════════════════ -->
                
                <!-- إدارة السلايدرات -->
                <li class="{{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sliders.index') }}">
                        <span>السلايدرات</span>
                        <i class="fas fa-images"></i>
                    </a>
                </li>
                
                <!-- الأسئلة الشائعة -->
                <li class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.faqs.index') }}">
                        <span>الأسئلة الشائعة</span>
                        <i class="fas fa-question-circle"></i>
                    </a>
                </li>
                
                <!-- قنوات الدعم -->
                <li class="{{ request()->routeIs('admin.support-channels.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.support-channels.index') }}">
                        <span>قنوات الدعم</span>
                        <i class="fas fa-headset"></i>
                    </a>
                </li>
                
                <!-- طرق التواصل -->
                <li class="{{ request()->routeIs('admin.contact-methods.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contact-methods.index') }}">
                        <span>طرق التواصل</span>
                        <i class="fas fa-address-book"></i>
                    </a>
                </li>
                
                <!-- سجل الأخطاء -->
                <li class="{{ request()->routeIs('admin.errors.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.errors.index') }}">
                        <span>سجل الأخطاء</span>
                        <i class="fas fa-exclamation-triangle"></i>
                    </a>
                </li>
            </ul>
            </div>
        </nav>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Sidebar toggle with localStorage
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            
            // Load saved state immediately to prevent flash
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
            
            sidebarCollapse.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });
            
            // Notification dropdown toggle
            const notificationIcon = document.querySelector('.notification-icon');
            const notificationPanel = document.querySelector('.notification-panel');
            
            notificationIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationPanel.classList.toggle('show');
            });
            
            // Close notification panel when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationIcon.contains(e.target) && !notificationPanel.contains(e.target)) {
                    notificationPanel.classList.remove('show');
                }
            });
        });
        
        // Confirmation for logout
        document.querySelector('form[method="POST"][action$="logout"]').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "هل تريد حقاً تسجيل الخروج؟",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، تسجيل الخروج',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
        
        // Show success messages with Toast
        @if(session('success'))
            Toastify({
                text: "{{ session('success') }}",
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#28a745",
                stopOnFocus: true
            }).showToast();
        @endif
        
        // Show error messages with Toast
        @if(session('error'))
            Toastify({
                text: "{{ session('error') }}",
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
                stopOnFocus: true
            }).showToast();
        @endif
    </script>

    <!-- Global Loading Script -->
    <script>
        // Global loading functions
        function showGlobalLoading() {
            document.getElementById('global-loading-overlay').style.display = 'flex';
        }
        
        function hideGlobalLoading() {
            document.getElementById('global-loading-overlay').style.display = 'none';
        }

        // Toggle notification panel
        function toggleNotificationPanel() {
            const panel = document.querySelector('.notification-panel');
            const body = document.body;
            
            if (panel) {
                // Close all other dropdowns first
                document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
                
                // Toggle panel
                const isVisible = panel.classList.contains('show');
                if (isVisible) {
                    panel.classList.remove('show');
                    body.classList.remove('notification-open');
                } else {
                    panel.classList.add('show');
                    body.classList.add('notification-open');
                    loadRecentNotifications();
                }
            }
        }

        // Close notification panel when clicking outside
        document.addEventListener('click', function(event) {
            const panel = document.querySelector('.notification-panel');
            const icon = document.querySelector('.notification-icon');
            if (panel && !panel.contains(event.target) && !icon.contains(event.target)) {
                panel.classList.remove('show');
            }
        });

        // Load notification count
        function loadNotificationCount() {
            fetch('/api/v1/admin/notifications/unread-count')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update sidebar notification badge
                    const sidebarBadge = document.getElementById('notification-count');
                    // Update topbar notification badge
                    const topbarBadge = document.getElementById('topbar-notification-count');
                    
                    if (data.count > 0) {
                        if (sidebarBadge) {
                            sidebarBadge.textContent = data.count;
                            sidebarBadge.style.display = 'inline';
                        }
                        if (topbarBadge) {
                            topbarBadge.textContent = data.count;
                            topbarBadge.style.display = 'flex';
                        }
                    } else {
                        if (sidebarBadge) {
                            sidebarBadge.style.display = 'none';
                        }
                        if (topbarBadge) {
                            topbarBadge.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notification count:', error);
                    // Hide the notification badges on error
                    const sidebarBadge = document.getElementById('notification-count');
                    const topbarBadge = document.getElementById('topbar-notification-count');
                    if (sidebarBadge) {
                        sidebarBadge.style.display = 'none';
                    }
                    if (topbarBadge) {
                        topbarBadge.style.display = 'none';
                    }
                });
        }

        // Load recent notifications
        function loadRecentNotifications() {
            fetch('/api/v1/admin/notifications/recent')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const notificationList = document.getElementById('notification-list');
                    if (data.length === 0) {
                        notificationList.innerHTML = `
                            <div class="p-4 text-center">
                                <i class="mb-2 fas fa-bell-slash text-muted" style="font-size: 2rem;"></i>
                                <div class="text-muted">لا توجد إشعارات جديدة</div>
                            </div>
                        `;
                    } else {
                        notificationList.innerHTML = data.map(notification => `
                            <div class="notification-item ${!notification.is_read ? 'unread' : ''}" onclick="markAsRead(${notification.id})">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon-small me-3">
                                        <i class="fas fa-${notification.icon || 'bell'} text-${notification.color || 'primary'}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="title">${notification.title}</div>
                                        <div class="text-muted small">${notification.message}</div>
                                        <div class="time">${notification.time_ago}</div>
                                    </div>
                                    ${!notification.is_read ? '<div class="unread-indicator"></div>' : ''}
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const notificationList = document.getElementById('notification-list');
                    notificationList.innerHTML = `
                        <div class="p-3 text-center">
                            <i class="mb-2 fas fa-exclamation-triangle text-warning"></i>
                            <div class="text-muted">خطأ في تحميل الإشعارات</div>
                        </div>
                    `;
                });
        }

        // Mark notification as read
        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotificationCount();
                    loadRecentNotifications();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        // Mark all notifications as read
        function markAllAsRead() {
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotificationCount();
                    loadRecentNotifications();
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
        }

        // Mark single notification as read
        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotificationCount();
                    loadRecentNotifications();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        // Show loading on page navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Load notification count and recent notifications on page load
            loadNotificationCount();
            loadRecentNotifications();
            
            // Refresh notification count and recent notifications every 30 seconds
            setInterval(() => {
                loadNotificationCount();
                loadRecentNotifications();
            }, 30000);
            
            // Add click event listener to notification icon
            const notificationIcon = document.querySelector('.notification-icon');
            if (notificationIcon) {
                notificationIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleNotificationPanel();
                });
            }
            // Show loading on all link clicks
            document.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' && e.target.href && !e.target.href.includes('#')) {
                    showGlobalLoading();
                }
            });

            // Show loading on form submissions
            document.addEventListener('submit', function(e) {
                if (e.target.tagName === 'FORM') {
                    showGlobalLoading();
                }
            });

            // Hide loading when page is fully loaded
            window.addEventListener('load', function() {
                hideGlobalLoading();
            });

            // Hide loading after a short delay to ensure smooth transition
            setTimeout(function() {
                hideGlobalLoading();
            }, 500);
            
            // Submenu functionality
            const submenuToggles = document.querySelectorAll('.has-submenu > a');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    // Prevent default only for submenu toggles (those with href starting with #)
                    if (this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();
                        
                        // Get the submenu
                        const submenu = this.nextElementSibling;
                        
                        // Toggle the submenu
                        const isExpanded = submenu.classList.contains('show');
                        
                        // Close all other submenus in the same level
                        const parent = this.closest('ul');
                        const allSubmenus = parent.querySelectorAll('.submenu');
                        const allParents = parent.querySelectorAll('.has-submenu');
                        
                        allSubmenus.forEach(menu => {
                            if (menu !== submenu) {
                                menu.classList.remove('show');
                            }
                        });
                        
                        allParents.forEach(item => {
                            if (item !== this.parentElement) {
                                item.classList.remove('active');
                            }
                        });
                        
                        // Toggle current submenu
                        submenu.classList.toggle('show');
                        this.parentElement.classList.toggle('active');
                        
                        // Update aria-expanded attribute
                        this.setAttribute('aria-expanded', !isExpanded);
                    }
                });
            });
        });
    </script>
    
    <!-- Admin Settings JavaScript -->
    <script src="{{ asset('js/admin-settings.js') }}"></script>
    
    <!-- Page-specific scripts -->
    @stack('scripts')
</body>
</html>   
 <!-- Enhanced JavaScript -->
    <script>
        // Enhanced Loading Screen
        function showGlobalLoading() {
            const overlay = document.getElementById('global-loading-overlay');
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function hideGlobalLoading() {
            const overlay = document.getElementById('global-loading-overlay');
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Show loading on page navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Show loading for form submissions
            const forms = document.querySelectorAll('form:not([data-no-loading])');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    showGlobalLoading();
                });
            });

            // Show loading for navigation links
            const links = document.querySelectorAll('a[href]:not([data-no-loading]):not([href^="#"]):not([href^="javascript:"]):not([target="_blank"])');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    showGlobalLoading();
                });
            });

            // Hide loading when page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(hideGlobalLoading, 500);
            });
        });

        // Enhanced Notification Panel
        function toggleNotificationPanel() {
            const panel = document.querySelector('.notification-panel');
            const isVisible = panel.classList.contains('show');
            
            if (isVisible) {
                panel.classList.remove('show');
                document.body.classList.remove('notification-open');
            } else {
                panel.classList.add('show');
                document.body.classList.add('notification-open');
                
                // Add entrance animation to notification items
                const items = panel.querySelectorAll('.notification-item');
                items.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        item.style.transition = 'all 0.3s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, index * 100);
                });
            }
        }

        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (item) {
                        item.classList.remove('unread');
                        item.style.animation = 'fadeOut 0.3s ease-out';
                        setTimeout(() => {
                            item.remove();
                            updateNotificationCount();
                        }, 300);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function markAllAsRead() {
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const unreadItems = document.querySelectorAll('.notification-item.unread');
                    unreadItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.remove('unread');
                            item.style.animation = 'fadeOut 0.3s ease-out';
                            setTimeout(() => {
                                item.remove();
                            }, 300);
                        }, index * 100);
                    });
                    
                    setTimeout(() => {
                        updateNotificationCount();
                    }, unreadItems.length * 100 + 300);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function updateNotificationCount() {
            const badge = document.getElementById('topbar-notification-count');
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Close notification panel when clicking outside
        document.addEventListener('click', function(event) {
            const panel = document.querySelector('.notification-panel');
            const icon = document.querySelector('.notification-icon');
            
            if (!panel.contains(event.target) && !icon.contains(event.target)) {
                panel.classList.remove('show');
                document.body.classList.remove('notification-open');
            }
        });

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }

            // Submenu toggle
            const submenuToggles = document.querySelectorAll('.has-submenu > a');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const submenu = parent.querySelector('.submenu');
                    
                    parent.classList.toggle('active');
                    
                    if (submenu) {
                        submenu.classList.toggle('show');
                    }
                });
            });
        });

        // Add fade out animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(20px);
                }
            }
        `;
        document.head.appendChild(style);
    </script>    <!-- B
ootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast JS -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.js"></script>

    <!-- Enhanced JavaScript -->
    <script>
        // Enhanced Loading Screen
        function showGlobalLoading() {
            const overlay = document.getElementById('global-loading-overlay');
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function hideGlobalLoading() {
            const overlay = document.getElementById('global-loading-overlay');
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Show loading on page navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap components
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }

            // Submenu toggle with Bootstrap Collapse
            const submenuToggles = document.querySelectorAll('.has-submenu > a[data-bs-toggle="collapse"]');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const targetId = this.getAttribute('href');
                    const submenu = document.querySelector(targetId);
                    
                    if (submenu) {
                        const bsCollapse = new bootstrap.Collapse(submenu, {
                            toggle: true
                        });
                        
                        submenu.addEventListener('shown.bs.collapse', function() {
                            parent.classList.add('active');
                        });
                        
                        submenu.addEventListener('hidden.bs.collapse', function() {
                            parent.classList.remove('active');
                        });
                    }
                });
            });

            // Show loading for form submissions
            const forms = document.querySelectorAll('form:not([data-no-loading])');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    showGlobalLoading();
                });
            });

            // Show loading for navigation links
            const links = document.querySelectorAll('a[href]:not([data-no-loading]):not([href^="#"]):not([href^="javascript:"]):not([target="_blank"])');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    showGlobalLoading();
                });
            });

            // Hide loading when page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(hideGlobalLoading, 500);
            });
        });

        // Enhanced Notification Panel
        function toggleNotificationPanel() {
            const panel = document.querySelector('.notification-panel');
            const isVisible = panel.classList.contains('show');
            
            if (isVisible) {
                panel.classList.remove('show');
                document.body.classList.remove('notification-open');
            } else {
                panel.classList.add('show');
                document.body.classList.add('notification-open');
                
                // Add entrance animation to notification items
                const items = panel.querySelectorAll('.notification-item');
                items.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        item.style.transition = 'all 0.3s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, index * 100);
                });
            }
        }

        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (item) {
                        item.classList.remove('unread');
                        item.style.animation = 'fadeOut 0.3s ease-out';
                        setTimeout(() => {
                            item.remove();
                            updateNotificationCount();
                        }, 300);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function markAllAsRead() {
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const unreadItems = document.querySelectorAll('.notification-item.unread');
                    unreadItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.remove('unread');
                            item.style.animation = 'fadeOut 0.3s ease-out';
                            setTimeout(() => {
                                item.remove();
                            }, 300);
                        }, index * 100);
                    });
                    
                    setTimeout(() => {
                        updateNotificationCount();
                    }, unreadItems.length * 100 + 300);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function updateNotificationCount() {
            const badge = document.getElementById('topbar-notification-count');
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Close notification panel when clicking outside
        document.addEventListener('click', function(event) {
            const panel = document.querySelector('.notification-panel');
            const icon = document.querySelector('.notification-icon');
            
            if (panel && icon && !panel.contains(event.target) && !icon.contains(event.target)) {
                panel.classList.remove('show');
                document.body.classList.remove('notification-open');
            }
        });

        // Add fade out animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(20px);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>