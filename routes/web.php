<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Models\Course;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 1. RUTE PUBLIK
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');


// 2. RUTE UNTUK USER YANG SUDAH LOGIN
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Pengguna Biasa
    Route::get('/dashboard', function () {
        // Ambil semua data yang dibutuhkan oleh Dashboard.vue
        $userStats = User::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $totalUsers = User::count();
        $totalProducts = Product::count(); // Anda bisa ganti ini dengan Course::count() jika mau
        $recentUsers = User::latest()->take(5)->get();
        $recentProducts = Product::latest()->take(5)->get(); // Ganti dengan Course::latest() jika mau

        return Inertia::render('Dashboard', [
            'userRegistrationStats' => $userStats,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'recentUsers' => $recentUsers,
            'recentProducts' => $recentProducts,
            'breadcrumbs' => [], // Kirim array kosong jika tidak ada breadcrumbs
        ]);
    })->name('dashboard');

    // Pengaturan Profil & Password (untuk semua user)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    });

});


// 3. RUTE KHUSUS ADMIN
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::resource('courses', AdminCourseController::class);
});


// 4. RUTE AUTENTIKASI (Login, Register, dll)
require __DIR__.'/auth.php';
