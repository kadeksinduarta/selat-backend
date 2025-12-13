<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ========================================
    // USER AUTHENTICATION (Public)
    // ========================================

    public function registerUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user',
        ]);

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }
        
        if ($user->role !== 'user') {
            return response()->json(['message' => 'Akun ini bukan akun user'], 403);
        }
        
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $user->createToken('user_token')->plainTextToken;

        // Track last login
        $user->update(['last_login' => now()]);

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user->fresh(),
        ]);
    }

    // ========================================
    // ADMIN AUTHENTICATION (Internal)
    // ========================================

    public function registerAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'admin',
        ]);

        return response()->json([
            'message' => 'Registrasi admin berhasil',
            'admin' => $admin,
        ], 201);
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }
        
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Hanya admin yang bisa login'], 403);
        }
        
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        // Track last login
        $user->update(['last_login' => now()]);

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'admin' => $user->fresh(),
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
