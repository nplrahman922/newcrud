<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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
    // Mengambil data jumlah user yang daftar dalam 30 hari terakhir, dikelompokkan per hari.
    $userStats = User::query()
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    return Inertia::render('Dashboard', [
        // Kirim data sebagai prop bernama 'userRegistrationStats'
        'userRegistrationStats' => $userStats
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
