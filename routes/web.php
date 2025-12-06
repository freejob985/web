<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});








// Dashboard route







Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin routes
Route::prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});

// Vendor routes
Route::prefix('vendor')->group(function () {
    Route::get('/login', [\App\Http\Controllers\VendorController::class, 'login'])->name('vendor.login');
    Route::post('/login', [\App\Http\Controllers\VendorController::class, 'authenticate'])->name('vendor.login.submit');
    Route::get('/signup', [\App\Http\Controllers\VendorController::class, 'signup'])->name('vendor.signup');
    Route::post('/signup', [\App\Http\Controllers\VendorController::class, 'register'])->name('vendor.register');
    
    Route::middleware('auth:vendor')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\VendorController::class, 'dashboard'])->name('vendor.dashboard');
        Route::post('/logout', [\App\Http\Controllers\VendorController::class, 'logout'])->name('vendor.logout');
    });
});

// Static Pages routes
Route::get('/terms', function () {
    return view('static-pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('static-pages.privacy');
})->name('privacy');

Route::get('/refund', function () {
    return view('static-pages.refund');
})->name('refund');


require __DIR__.'/dev.php';