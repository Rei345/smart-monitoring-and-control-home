<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('index');
});
Route::view('/tentang', 'tentang');
Route::view('/metode', 'metode');
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak/kirim', [ContactController::class, 'send'])->name('contact.send');
Route::get('/tampilan', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/save-config', [DashboardController::class, 'updateConfig'])->name('config.update');

// Route untuk Login/Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard & Config hanya bisa diakses kalau sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/save-config', [DashboardController::class, 'updateConfig'])->name('config.update');
    Route::post('/dashboard/save-schedule', [DashboardController::class, 'updateSchedule'])->name('schedule.update');
});
