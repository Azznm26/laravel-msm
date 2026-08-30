<?php

namespace App\Http\Controllers\Api;

use App\Models\User; // ✅ Wajib untuk $user = User::create(...)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // ✅ Wajib untuk Hash::make(...)
use Illuminate\Support\Facades\Validator; // ✅ Wajib untuk Validator::make(...)

// HAPUS kata "extends Controller" di bawah ini
class AuthController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'id_badge' => 'required|string|max:50|unique:users,id_badge', // <-- TAMBAHKAN INI
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'id_badge' => $request->id_badge, // <-- TAMBAHKAN INI
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil. Akun Anda sedang menunggu persetujuan HRD.',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // ✅ CEK STATUS APPROVAL SEBELUM MEMBERIKAN TOKEN
            // Ganti 'is_approved' sesuai dengan nama kolom di web-mu (misal: $user->status !== 'active')
            if (!$user->status) {
                // Hapus sesi attempt
                Auth::guard('web')->logout();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun Anda belum disetujui. Silakan hubungi Admin HR.'
                ], 403); // 403 Forbidden
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah'
        ], 401);
    }

    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout dan token dihapus'
        ]);
    }
}
