<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Userfile;

class EnsureUserfileAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated via Sanctum, ensure it's a Userfile instance
        if ($request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            
            if ($token && $token->tokenable_type === Userfile::class) {
                // Get the Userfile instance
                $userfile = Userfile::find($token->tokenable_id);
                
                if ($userfile) {
                    // Set the authenticated user to Userfile instance
                    auth()->setUser($userfile);
                }
            }
        }

        return $next($request);
    }
}

