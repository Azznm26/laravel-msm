// app/Http/Controllers/Api/DeviceTokenController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'platform' => 'nullable|string|in:android,ios',
        ]);

        // Hapus token yang sama di user lain (kalau ganti akun di device yang sama)
        \App\Models\DeviceToken::where('token', $request->token)
            ->where('user_id', '!=', $request->user()->id)
            ->delete();

        $request->user()->deviceTokens()->updateOrCreate(
            ['token' => $request->token],
            ['platform' => $request->platform ?? 'android']
        );

        return response()->json(['message' => 'Token perangkat terdaftar.']);
    }

    public function unregister(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $request->user()->deviceTokens()->where('token', $request->token)->delete();

        return response()->json(['message' => 'Token perangkat dihapus.']);
    }
}
