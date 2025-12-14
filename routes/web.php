<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FilmController;
use App\Http\Controllers\Admin\CategoryController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [HomeController::class, 'browse'])->name('browse');
Route::get('/film/{slug}', [HomeController::class, 'show'])->name('film.show');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/verify-otp', [AuthController::class, 'showOtpVerify'])->name('otp.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/watch/{slug}', [HomeController::class, 'watch'])->name('film.watch');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Film Management
    Route::resource('films', FilmController::class);
    
    // Category Management
    Route::resource('categories', CategoryController::class);
});

