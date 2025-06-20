<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\UserProfile;

class UserProfileController extends Controller
{
    // Mengambil profil pengguna yang sedang login
    public function show()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json(['message' => 'Profil tidak ditemukan.'], 404);
        }

        return response()->json($profile);
    }

    // Membuat atau memperbarui profil pengguna
    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string|url',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
        ]);

        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['phone', 'bio', 'avatar', 'gender', 'birth_date'])
        );

        return response()->json($profile);
    }
}