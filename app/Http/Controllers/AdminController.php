<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of admins (role='admin' only)
     */
    public function index(): JsonResponse
    {
        $admins = User::where('role', 'admin')
            ->select('id', 'name', 'email', 'last_login', 'created_at')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dibuat',
            'data' => $admin
        ], 201);
    }

    /**
     * Display the specified admin
     */
    public function show($id): JsonResponse
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $admin
        ]);
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, $id): JsonResponse
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($admin->id)
            ],
            'password' => 'sometimes|string|min:6',
        ]);

        // Only update password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $admin->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil diupdate',
            'data' => $admin
        ]);
    }

    /**
     * Remove the specified admin
     */
    public function destroy($id): JsonResponse
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        
        // Prevent deleting the last admin
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus admin terakhir'
            ], 403);
        }

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dihapus'
        ]);
    }
}
