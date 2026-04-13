<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Form login
    public function loginForm()
    {
        return view('admin.login');
    }

    // Process login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Cek apakah user adalah admin
            if (!$user->isAdmin()) {
                Auth::logout();
                return back()->with('error', 'Anda tidak memiliki akses ke admin panel');
            }

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang Admin!');
        }

        return back()->with('error', 'Email atau password salah');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Anda telah logout');
    }

    // Register admin (hanya untuk setup awal)
    public function registerForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return redirect()->route('login')->with('success', 'Admin berhasil dibuat. Silakan login');
    }
}
