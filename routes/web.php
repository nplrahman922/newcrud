<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

// Controller untuk Admin
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;


// ... (Rute-rute yang sudah ada sebelumnya)

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    // Logika dashboard user biasa bisa ditambahkan di sini nanti
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';


// ====== GRUP RUTE UNTUK ADMIN PANEL ======
Route::middleware(['auth', 'verified', 'Admin'])->prefix('Admin')->name('Admin.')->group(function () {
    // Route untuk dashboard Admin bisa ditambahkan di sini nanti
    // Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Route untuk manajemen pengguna
    Route::resource('users', AdminUserController::class);

    // Route untuk manajemen kursus
    Route::resource('courses', AdminCourseController::class);
});
