<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('react-web')->plainTextToken;

        $minutes = 60 * 24 * 7; // 7 days
        $secure = $request->isSecure();
        $cookie = Cookie::make(
            'access_token',
            $token,
            $minutes,
            '/',
            null, // domain - null means current domain
            $secure,
            true, // httpOnly - prevents JS access
            false, // raw
            'Lax' // SameSite - works for same-origin requests
        );

        return response()
            ->json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ])
            ->cookie($cookie);
    }

    public function logout(Request $request): JsonResponse
    {
        // Delete the token from database
        $request->user()->currentAccessToken()->delete();

        // Delete the cookie
        $cookie = Cookie::forget('access_token');

        return response()
            ->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ])
            ->cookie($cookie);
    }
}

