<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Changa:wght@200..800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
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

        /* Enhanced Splash Screen */
        .splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.8s ease, transform 0.8s ease;
            overflow: hidden;
        }

        .splash-screen::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: gridMove 20s linear infinite;
        }

        .splash-screen.fade-out {
            opacity: 0;
            transform: scale(1.1) rotate(5deg);
            pointer-events: none;
        }

        .splash-logo {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
            animation: logoFloat 3s ease-in-out infinite;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.1);
            position: relative;
            z-index: 2;
        }

        .splash-logo::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(45deg, #667eea, #764ba2, #f093fb);
            border-radius: 50%;
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        .splash-logo i {
            font-size: 4rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: iconPulse 2s ease-in-out infinite;
        }

        .splash-title {
            color: white;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            animation: titleSlideIn 1.2s ease;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }

        .splash-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 1.4rem;
            margin-bottom: 50px;
            animation: subtitleSlideIn 1.2s ease 0.3s both;
            font-weight: 300;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255,255,255,0.2);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1.2s linear infinite;
            position: relative;
        }

        .loading-spinner::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: innerPulse 1.5s ease-in-out infinite;
        }

        .progress-bar {
            width: 200px;
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            margin-top: 30px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ffffff, #f093fb);
            border-radius: 2px;
            animation: progressFill 3s ease-in-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-10px, -10px); }
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes titleSlideIn {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes subtitleSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes innerPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.6; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 1; }
        }

        @keyframes progressFill {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="70" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="60" r="1.2" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="40" r="1.3" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 200px 200px;
            z-index: 0;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: auto;
            position: relative;
            z-index: 1;
        }
        
        .login-card {
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1);
            border: none;
            overflow: hidden;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            z-index: 1;
        }

        .login-card > * {
            position: relative;
            z-index: 2;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .login-header::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            background: inherit;
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
        }

        @keyframes shimmer {
            0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
            50% { transform: translate(-50%, -50%) rotate(180deg); }
        }
        
        .login-body {
            padding: 30px;
            background: white;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 15px 20px;
            border: 2px solid #e1e5f2;
            margin-bottom: 25px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8f9fa;
            font-size: 1rem;
            position: relative;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), 0 4px 12px rgba(102, 126, 234, 0.1);
            background: #fff;
            transform: translateY(-2px);
        }

        .form-control:hover {
            border-color: #b8c5f0;
            transform: translateY(-1px);
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        
        .form-label i {
            color: #3498db;
            font-size: 1rem;
            margin-left: 8px;
            width: 20px;
            text-align: center;
        }
        
        .input-group-text {
            background: linear-gradient(120deg, #2c3e50, #3498db);
            color: white;
            border: 1px solid #3498db;
            border-left: none;
            border-radius: 0 10px 10px 0 !important;
            width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .input-group .form-control {
            border-radius: 10px 0 0 10px !important;
            border-right: none;
            padding-right: 15px;
        }
        
        .input-group:focus-within .input-group-text {
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            background: linear-gradient(120deg, #34495e, #2980b9);
        }
        
        .form-check-label {
            display: flex;
            align-items: center;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-check-label i {
            color: #3498db;
            margin-left: 8px;
            font-size: 0.9rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border: none;
            border-radius: 12px;
            padding: 15px 25px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }
        
        .form-check-input:checked {
            background-color: #3498db;
            border-color: #3498db;
        }
        
        .alert-danger {
            border-radius: 10px;
        }
        
        .input-group-text {
            background: linear-gradient(120deg, #2c3e50, #3498db);
            color: white;
            border: 1px solid #3498db;
            border-left: none;
            border-radius: 0 10px 10px 0 !important;
        }
        
        .input-group .form-control {
            border-radius: 10px 0 0 10px !important;
            border-right: none;
        }
        
        .input-group:focus-within .input-group-text {
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .logo-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
            color: #3498db;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .password-toggle {
            cursor: pointer;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #6c757d;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .animate-icon {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Remove floating label styles */
    </style>
</head>
<body>
    <!-- Enhanced Splash Screen -->
    <div class="splash-screen" id="splashScreen">
        <div class="splash-logo">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h1 class="splash-title">لوحة التحكم</h1>
        <p class="splash-subtitle">نظام إدارة متكامل وآمن</p>
        <div class="loading-spinner"></div>
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-shield logo-icon animate-icon"></i>
                <h3><i class="fas fa-user-shield me-2"></i> تسجيل دخول الأدمن</h3>
                <p class="mb-0">مرحباً بك في لوحة التحكم</p>
            </div>
            
            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="أدخل بريدك الإلكتروني" 
                                   required 
                                   value="{{ old('email') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-key me-2"></i>كلمة المرور
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="أدخل كلمة المرور" 
                                   required>
                            <span class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            <i class="fas fa-check-circle me-2"></i>تذكرني
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-login text-white">
                        <i class="fas fa-sign-in-alt me-2"></i> تسجيل الدخول
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Enhanced Splash Screen Management
        document.addEventListener('DOMContentLoaded', function() {
            const splashScreen = document.getElementById('splashScreen');
            const progressFill = document.querySelector('.progress-fill');
            
            // Animate progress bar
            setTimeout(() => {
                progressFill.style.width = '100%';
            }, 100);
            
            // Hide splash screen after 4 seconds
            setTimeout(() => {
                splashScreen.classList.add('fade-out');
                setTimeout(() => {
                    splashScreen.style.display = 'none';
                }, 800);
            }, 4000);
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Enhanced Form submission with SweetAlert confirmation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if(email && password) {
                // Add loading animation to button
                const submitBtn = document.querySelector('.btn-login');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحقق...';
                submitBtn.disabled = true;
                
                Swal.fire({
                    title: 'جاري تسجيل الدخول...',
                    text: 'يرجى الانتظار بينما نتحقق من بياناتك',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                        // Submit the form after a short delay to show the loading
                        setTimeout(() => {
                            e.target.submit();
                        }, 1500);
                    }
                });
            } else {
                Swal.fire({
                    title: 'خطأ!',
                    text: 'يرجى ملء جميع الحقول المطلوبة',
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
            }
        });

        // Add focus effects to form inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Show error toast if exists
        @if ($errors->any())
            Toastify({
                text: "{{ $errors->first() }}",
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
                stopOnFocus: true
            }).showToast();
        @endif
    </script>
</body>
</html>