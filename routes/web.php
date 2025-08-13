<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('products', ProductController::class);
});
Route::get('/dashboard', function () {
    // 1. Data untuk grafik (tetap sama)
    $userStats = User::query()
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    // 2. Data untuk Kartu Statistik (BARU)
    $totalUsers = User::count();
    $totalProducts = Product::count();

    // 3. Data untuk Aktivitas Terbaru (BARU)
    $recentUsers = User::latest()->take(5)->get();

    // 4. Data untuk Produk Terbaru (BARU)
    $recentProducts = Product::latest()->take(5)->get();


    return Inertia::render('Dashboard', [
        'userRegistrationStats' => $userStats,
        'totalUsers' => $totalUsers,
        'totalProducts' => $totalProducts,
        'recentUsers' => $recentUsers,
        'recentProducts' => $recentProducts, // <-- Kirim data baru
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
