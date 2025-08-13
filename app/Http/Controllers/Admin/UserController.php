<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:Admin,user',
            'access_duration' => 'required|string', // e.g., '1_month', '3_months', '1_year', 'unlimited'
        ]);

        $expires_at = null;
        if ($request->access_duration !== 'unlimited') {
            $parts = explode('_', $request->access_duration); // [1, 'month']
            $value = (int) $parts[0];
            $unit = $parts[1];
            $expires_at = Carbon::now()->add($unit, $value);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'access_expires_at' => $expires_at,
            'email_verified_at' => now(), // Anggap email terverifikasi jika dibuat Admin
        ]);

        return redirect()->route('Admin.users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:Admin,user',
            'access_duration' => 'required|string',
        ]);

        $expires_at = $user->access_expires_at;
        if ($request->access_duration !== 'keep_current') {
            if ($request->access_duration === 'unlimited') {
                $expires_at = null;
            } else {
                $parts = explode('_', $request->access_duration);
                $value = (int) $parts[0];
                $unit = $parts[1];
                // Jika memperpanjang, tambahkan durasi dari sekarang.
                // Jika ingin dari tanggal expired, gunakan: Carbon::parse($user->access_expires_at ?? now())->add($unit, $value)
                $expires_at = Carbon::now()->add($unit, $value);
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'access_expires_at' => $expires_at,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('Admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Jangan biarkan Admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('Admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('Admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
