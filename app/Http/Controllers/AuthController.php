<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Userfile;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Find user in userfile table
        $userfile = Userfile::where('usrcde', $data['username'])->first();

        if (!$userfile) {
            throw ValidationException::withMessages([
                'username' => ['Invalid credentials.'],
            ]);
        }

        // Verify password using SHA1
        $hashedPassword = sha1($data['password']);
        if ($userfile->usrpwd !== $hashedPassword) {
            throw ValidationException::withMessages([
                'username' => ['Invalid credentials.'],
            ]);
        }

        $userfile->save();

        // Create token directly on Userfile model
        $token = $userfile->createToken('react-web')->plainTextToken;

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
                    'username' => $userfile->usrcde,
                    'name' => $userfile->usrcde,
                    'monitorsetup' => $userfile->monitorsetup,
                ],
            ])
            ->cookie($cookie);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        // Delete the token from database if it's a PersonalAccessToken
        // (not a TransientToken from session-based auth)
        if ($request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if ($token) {
                $token->delete();
            }
        } else {
            // If using session-based auth, revoke all tokens for this user
            $user->tokens()->delete();
        }

        // Delete the cookie
        $cookie = Cookie::forget('access_token');

        return response()
            ->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ])
            ->cookie($cookie);
    }

    /**
     * Clear all tokens (for testing only)
     */
    public function clearAllTokens(): JsonResponse
    {
        DB::table('personal_access_tokens')->truncate();

        return response()->json([
            'success' => true,
            'message' => 'All tokens cleared',
        ]);
    }
}

