<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
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
            'message' => 'Registrasi berhasil',
            'admin' => $admin,
        ], 201);
    }

    public function login(Request $request)
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

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'admin' => $user,
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
