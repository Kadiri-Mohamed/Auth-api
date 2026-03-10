<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ], 200);
    }
    public function updatePassword(Request $request)
    {
        $user = auth('api')->user();

        $data = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);
    }
    public function deleteAccount(Request $request)
    {
        $user = auth('api')->user();

        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully'
        ], 200);
    }

    public function showProfile()
    {
        $user = auth('api')->user();

        return response()->json([
            'user' => $user
        ], 200);
    }
}
